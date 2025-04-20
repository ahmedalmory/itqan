<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\StudyCircle;
use App\Models\Surah;
use App\Models\StudentPoint;
use App\Models\StudentSubscription;
use App\Models\SubscriptionPlan;
use App\Models\CircleStudent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get student's circle information with department, teacher and supervisor
        $circle = StudyCircle::with(['department', 'teacher', 'supervisor'])
            ->whereHas('students', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->first();
        
        // Get student's points from all circles
        $points_data = DB::table('circle_students as cs')
            ->join('study_circles as c', 'cs.circle_id', '=', 'c.id')
            ->leftJoin('student_points as sp', function($join) {
                $join->on('sp.student_id', '=', 'cs.student_id')
                    ->on('sp.circle_id', '=', 'cs.circle_id');
            })
            ->select(
                'c.name as circle_name',
                DB::raw('COALESCE(sp.total_points, 0) as points'),
                DB::raw('(SELECT COUNT(*) + 1 FROM student_points sp2 WHERE sp2.circle_id = c.id AND sp2.total_points > COALESCE(sp.total_points, 0)) as student_rank'),
                DB::raw('(SELECT COUNT(*) FROM circle_students cs2 WHERE cs2.circle_id = c.id) as total_students')
            )
            ->where('cs.student_id', $user->id)
            ->get();
        
        // Calculate total points
        $total_points = $points_data->sum('points');
        
        // Get active subscription
        $active_subscription = StudentSubscription::with('plan', 'circle')
            ->where('student_id', $user->id)
            ->where('is_active', 1)
            ->where('end_date', '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->first();
            
        // Get available subscription plans if no active subscription
        $subscription_plans = $active_subscription ? collect() : SubscriptionPlan::where('is_active', 1)->orderBy('lessons_per_month', 'asc')->get();
        
        // Get working days for the student's department
        $work_days = $this->getStudentDepartmentWorkDays($user->id);
        
        // Get reports for the last 7 days
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $day_name = strtolower(Carbon::now()->subDays($i)->format('l'));
            $is_working_day = $work_days["work_$day_name"] ?? false;
            
            $report = $this->getStudentDailyReport($user->id, $date);
            $card_color = $this->getReportCardColor($report, $is_working_day);
            
            $days[] = [
                'date' => $date,
                'day_name' => $day_name,
                'is_working_day' => $is_working_day,
                'report' => $report,
                'card_color' => $card_color
            ];
        }
        
        // Calculate progress statistics
        $stats = DailyReport::where('student_id', $user->id)
            ->select(
                DB::raw('AVG(grade) as avg_grade'),
                DB::raw('SUM(memorization_parts) as total_memorization'),
                DB::raw('SUM(revision_parts) as total_revision'),
                DB::raw('COUNT(*) as total_reports')
            )
            ->first();
        
        return view('student.dashboard', compact(
            'circle', 
            'days',
            'stats',
            'total_points',
            'points_data',
            'active_subscription',
            'subscription_plans'
        ));
    }
    
    /**
     * Get the student's daily report for a specific date
     *
     * @param int $student_id
     * @param string $date
     * @return array|null
     */
    private function getStudentDailyReport($student_id, $date)
    {
        $report = DailyReport::with(['fromSurah', 'toSurah'])
            ->where('student_id', $student_id)
            ->where('report_date', $date)
            ->first();
            
        if (!$report) {
            return null;
        }
        
        return [
            'id' => $report->id,
            'memorization_parts' => $report->memorization_parts,
            'revision_parts' => $report->revision_parts,
            'grade' => $report->grade,
            'from_surah_name' => $report->fromSurah->name,
            'to_surah_name' => $report->toSurah->name,
            'memorization_from_verse' => $report->memorization_from_verse,
            'memorization_to_verse' => $report->memorization_to_verse,
        ];
    }
    
    /**
     * Get the working days for the student's department
     *
     * @param int $student_id
     * @return array
     */
    private function getStudentDepartmentWorkDays($student_id)
    {
        $work_days = CircleStudent::join('study_circles', 'circle_students.circle_id', '=', 'study_circles.id')
            ->join('departments', 'study_circles.department_id', '=', 'departments.id')
            ->where('circle_students.student_id', $student_id)
            ->select([
                'departments.work_sunday', 
                'departments.work_monday', 
                'departments.work_tuesday', 
                'departments.work_wednesday', 
                'departments.work_thursday', 
                'departments.work_friday', 
                'departments.work_saturday'
            ])
            ->first();
            
        return $work_days ? $work_days->toArray() : [
            'work_sunday' => false,
            'work_monday' => true,
            'work_tuesday' => true,
            'work_wednesday' => true,
            'work_thursday' => true,
            'work_friday' => false,
            'work_saturday' => false,
        ];
    }
    
    /**
     * Determine the report card color based on the report data and working day
     *
     * @param array|null $report
     * @param bool $is_working_day
     * @return string
     */
    private function getReportCardColor($report, $is_working_day)
    {
        if (!$is_working_day && !$report) {
            return 'holiday';
        }
        
        if (!$report) {
            return 'no-report';
        }
        
        if ($report['grade'] >= 90) {
            return 'excellent';
        }
        
        if ($report['grade'] >= 75) {
            return 'good';
        }
        
        if ($report['grade'] >= 60) {
            return 'pass';
        }
        
        return 'poor';
    }
} 