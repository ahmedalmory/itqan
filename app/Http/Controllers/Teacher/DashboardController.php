<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudyCircle;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher');
    }

    /**
     * Show the teacher dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get teacher's circles with student count
        $circles = StudyCircle::where('teacher_id', $user->id)
            ->with('department')
            ->withCount('students')
            ->get();
        
        // Get today's attendance and reports count
        $today = date('Y-m-d');
        $attendance = DB::table('study_circles as c')
            ->select(
                'c.id as circle_id',
                'c.name as circle_name',
                DB::raw('COUNT(DISTINCT cs.student_id) as total_students'),
                DB::raw('COUNT(DISTINCT dr.student_id) as reports_submitted')
            )
            ->join('circle_students as cs', 'c.id', '=', 'cs.circle_id')
            ->leftJoin('daily_reports as dr', function($join) use ($today) {
                $join->on('cs.student_id', '=', 'dr.student_id')
                    ->where('dr.report_date', '=', $today);
            })
            ->where('c.teacher_id', $user->id)
            ->groupBy('c.id', 'c.name')
            ->get();
            
        // Get recent daily reports
        $recentReports = DailyReport::select(
                'daily_reports.*', 
                'users.name as student_name',
                'from_surah.name as from_surah',
                'to_surah.name as to_surah',
                'study_circles.name as circle_name'
            )
            ->join('users', 'daily_reports.student_id', '=', 'users.id')
            ->join('surahs as from_surah', 'daily_reports.memorization_from_surah', '=', 'from_surah.id')
            ->join('surahs as to_surah', 'daily_reports.memorization_to_surah', '=', 'to_surah.id')
            ->join('circle_students', 'daily_reports.student_id', '=', 'circle_students.student_id')
            ->join('study_circles', 'circle_students.circle_id', '=', 'study_circles.id')
            ->where('study_circles.teacher_id', $user->id)
            ->orderBy('daily_reports.report_date', 'desc')
            ->orderBy('daily_reports.created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Calculate overall statistics
        $totalStudents = 0;
        $totalAttendance = 0;
        foreach ($attendance as $circle) {
            $totalStudents += $circle->total_students;
            $totalAttendance += $circle->reports_submitted;
        }
        
        return view('teacher.dashboard', compact(
            'circles', 
            'attendance', 
            'recentReports', 
            'totalStudents', 
            'totalAttendance'
        ));
    }
} 