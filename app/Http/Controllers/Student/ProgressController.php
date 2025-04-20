<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\StudentPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    /**
     * Display the student's progress overview.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get the student's most recent reports
        $reports = DailyReport::where('student_id', $user->id)
            ->orderBy('report_date', 'desc')
            ->take(10)
            ->get();
            
        // Calculate total memorization parts
        $totalMemorization = DailyReport::where('student_id', $user->id)
            ->sum('memorization_parts');
            
        // Calculate total revision parts
        $totalRevision = DailyReport::where('student_id', $user->id)
            ->sum('revision_parts');
            
        // Calculate average grade
        $averageGrade = DailyReport::where('student_id', $user->id)
            ->avg('grade');
            
        // Get student points by circle
        $pointsByCircle = StudentPoint::where('student_id', $user->id)
            ->with('circle')
            ->get()
            ->groupBy('circle_id');
            
        // Calculate memorization progress by month
        $monthlyProgress = DailyReport::where('student_id', $user->id)
            ->select(
                DB::raw('YEAR(report_date) as year'),
                DB::raw('MONTH(report_date) as month'),
                DB::raw('SUM(memorization_parts) as total_memorization'),
                DB::raw('SUM(revision_parts) as total_revision'),
                DB::raw('AVG(grade) as average_grade')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();
            
        return view('student.progress.index', compact(
            'reports',
            'totalMemorization',
            'totalRevision',
            'averageGrade',
            'pointsByCircle',
            'monthlyProgress'
        ));
    }
    
    /**
     * Display the student's points history.
     *
     * @return \Illuminate\View\View
     */
    public function points()
    {
        $user = Auth::user();
        
        // Get the student's points with pagination
        $pointsHistory = DB::table('points_history')
            ->where('student_id', $user->id)
            ->join('study_circles', 'points_history.circle_id', '=', 'study_circles.id')
            ->join('users as creators', 'points_history.created_by', '=', 'creators.id')
            ->select(
                'points_history.*', 
                'study_circles.name as circle_name',
                'creators.name as created_by_name'
            )
            ->orderBy('points_history.created_at', 'desc')
            ->paginate(15);
            
        // Get total points by circle
        $totalPointsByCircle = DB::table('student_points')
            ->where('student_id', $user->id)
            ->join('study_circles', 'student_points.circle_id', '=', 'study_circles.id')
            ->select(
                'student_points.circle_id',
                'study_circles.name as circle_name',
                'student_points.points'
            )
            ->get();
            
        return view('student.progress.points', compact('pointsHistory', 'totalPointsByCircle'));
    }
    
    /**
     * Display the student's attendance record.
     *
     * @return \Illuminate\View\View
     */
    public function attendance()
    {
        $user = Auth::user();
        
        // Get reports grouped by month for attendance tracking
        $attendanceByMonth = DailyReport::where('student_id', $user->id)
            ->select(
                DB::raw('YEAR(report_date) as year'),
                DB::raw('MONTH(report_date) as month'),
                DB::raw('COUNT(*) as days_attended')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
            
        // Get the student's circle to determine working days
        $userCircle = $user->circles()->first();
        $workingDaysCount = null;
        
        if ($userCircle) {
            $department = $userCircle->department;
            
            // Count working days in the department
            $workingDaysCount = array_sum([
                $department->work_monday ? 1 : 0,
                $department->work_tuesday ? 1 : 0,
                $department->work_wednesday ? 1 : 0,
                $department->work_thursday ? 1 : 0,
                $department->work_friday ? 1 : 0,
                $department->work_saturday ? 1 : 0,
                $department->work_sunday ? 1 : 0
            ]);
        }
        
        return view('student.progress.attendance', compact('attendanceByMonth', 'workingDaysCount'));
    }
} 