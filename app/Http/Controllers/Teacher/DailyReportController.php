<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\StudyCircle;
use App\Models\User;
use App\Models\Surah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DailyReportController extends Controller
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
     * Display a listing of daily reports.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get all circles taught by this teacher
        $circles = StudyCircle::where('teacher_id', $user->id)
            ->withCount('students')
            ->get();
            
        // Get selected circle
        $selectedCircleId = $request->input('circle_id', $circles->first()->id ?? null);
        
        // If no circle is selected or circle doesn't belong to teacher, redirect
        if (!$selectedCircleId || !$circles->contains('id', $selectedCircleId)) {
            if ($circles->isNotEmpty()) {
                return redirect()->route('teacher.daily-reports.index', ['circle_id' => $circles->first()->id]);
            }
            
            return view('teacher.daily-reports.index', [
                'circles' => $circles,
                'students' => collect(),
                'selectedCircle' => null,
                'surahs' => collect(),
                'date' => now()->format('Y-m-d')
            ]);
        }
        
        $selectedCircle = $circles->firstWhere('id', $selectedCircleId);
        
        // Get students in the selected circle with their reports for today
        $date = $request->input('date', now()->format('Y-m-d'));
        
        $students = User::whereHas('circles', function($query) use ($selectedCircleId) {
                $query->where('study_circles.id', $selectedCircleId);
            })
            ->with(['dailyReports' => function($query) use ($date) {
                $query->where('report_date', $date);
            }])
            ->get();
            
        // Get all surahs for the form
        $surahs = Surah::orderBy('id')->get();
        
        return view('teacher.daily-reports.index', [
            'circles' => $circles,
            'students' => $students,
            'selectedCircle' => $selectedCircle,
            'surahs' => $surahs,
            'date' => $date
        ]);
    }

    /**
     * Store a newly created daily report.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'circle_id' => 'required|exists:study_circles,id',
            'student_id' => 'required|exists:users,id',
            'report_date' => 'required|date',
            'memorization_from_surah' => 'required|exists:surahs,id',
            'memorization_from_verse' => 'required|integer|min:1',
            'memorization_to_surah' => 'required|exists:surahs,id',
            'memorization_to_verse' => 'required|integer|min:1',
            'memorization_parts' => 'required|numeric|min:0.25|max:30',
            'grade' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Verify that the circle belongs to this teacher
        $circle = StudyCircle::findOrFail($request->circle_id);
        if ($circle->teacher_id !== $user->id) {
            return redirect()->back()
                ->with('error', t('unauthorized_action'))
                ->withInput();
        }
        
        // Verify that the student is in the circle
        $studentInCircle = DB::table('circle_students')
            ->where('circle_id', $request->circle_id)
            ->where('student_id', $request->student_id)
            ->exists();
            
        if (!$studentInCircle) {
            return redirect()->back()
                ->with('error', t('student_not_in_circle'))
                ->withInput();
        }
        
        // Check if report already exists for this date
        $existingReport = DailyReport::where('student_id', $request->student_id)
            ->where('report_date', $request->report_date)
            ->first();
            
        if ($existingReport) {
            // Update existing report
            $existingReport->update([
                'memorization_from_surah' => $request->memorization_from_surah,
                'memorization_from_verse' => $request->memorization_from_verse,
                'memorization_to_surah' => $request->memorization_to_surah,
                'memorization_to_verse' => $request->memorization_to_verse,
                'memorization_parts' => $request->memorization_parts,
                'grade' => $request->grade,
                'notes' => $request->notes,
            ]);
            
            return redirect()->back()
                ->with('success', t('report_updated_successfully'));
        }
        
        // Create new report
        DailyReport::create([
            'student_id' => $request->student_id,
            'report_date' => $request->report_date,
            'memorization_from_surah' => $request->memorization_from_surah,
            'memorization_from_verse' => $request->memorization_from_verse,
            'memorization_to_surah' => $request->memorization_to_surah,
            'memorization_to_verse' => $request->memorization_to_verse,
            'memorization_parts' => $request->memorization_parts,
            'grade' => $request->grade,
            'notes' => $request->notes,
        ]);
        
        return redirect()->back()
            ->with('success', t('report_created_successfully'));
    }

    /**
     * Delete a daily report.
     *
     * @param DailyReport $report
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DailyReport $report)
    {
        $user = Auth::user();
        
        // Check if the report belongs to a student in teacher's circle
        $teacherCircles = StudyCircle::where('teacher_id', $user->id)->pluck('id');
        
        $studentInTeacherCircle = DB::table('circle_students')
            ->whereIn('circle_id', $teacherCircles)
            ->where('student_id', $report->student_id)
            ->exists();
            
        if (!$studentInTeacherCircle) {
            return redirect()->back()
                ->with('error', t('unauthorized_action'));
        }
        
        $report->delete();
        
        return redirect()->back()
            ->with('success', t('report_deleted_successfully'));
    }

    /**
     * View reports history.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $teacherCircles = StudyCircle::where('teacher_id', $user->id)->pluck('id');
        
        // Get all students in teacher's circles
        $studentsInCircles = DB::table('circle_students')
            ->whereIn('circle_id', $teacherCircles)
            ->pluck('student_id');
            
        // Build query for reports
        $reportsQuery = DailyReport::whereIn('student_id', $studentsInCircles)
            ->with(['student', 'fromSurah', 'toSurah']);
            
        // Apply filters if any
        if ($request->has('student_id') && $request->student_id) {
            $reportsQuery->where('student_id', $request->student_id);
        }
        
        if ($request->has('from_date') && $request->from_date) {
            $reportsQuery->where('report_date', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $reportsQuery->where('report_date', '<=', $request->to_date);
        }
        
        if ($request->has('circle_id') && $request->circle_id) {
            $studentsInSelectedCircle = DB::table('circle_students')
                ->where('circle_id', $request->circle_id)
                ->pluck('student_id');
                
            $reportsQuery->whereIn('student_id', $studentsInSelectedCircle);
        }
        
        // Order by date
        $reportsQuery->orderBy('report_date', 'desc');
        
        // Get results with pagination
        $reports = $reportsQuery->paginate(20);
        
        // Get all circles and students for filters
        $circles = StudyCircle::where('teacher_id', $user->id)->get();
        $students = User::whereIn('id', $studentsInCircles)->get();
        
        return view('teacher.daily-reports.history', [
            'reports' => $reports,
            'circles' => $circles,
            'students' => $students,
            'filters' => $request->only(['student_id', 'circle_id', 'from_date', 'to_date']),
        ]);
    }
} 