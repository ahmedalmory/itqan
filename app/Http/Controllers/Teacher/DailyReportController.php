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
use Illuminate\Validation\Rule;

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
        $circles = StudyCircle::where('teacher_id', $user->id)->get();
        $selectedCircle = null;
        $students = collect();
        $date = $request->get('date', now()->format('Y-m-d'));
        $surahs = Surah::orderBy('id')->get();
        
        if ($request->has('circle_id')) {
            $selectedCircle = $circles->firstWhere('id', $request->circle_id);
            if ($selectedCircle) {
                $students = $selectedCircle->students()
                    ->with(['dailyReports' => function($query) use ($date) {
                        $query->where('report_date', $date);
                    }])
                    ->get();
            }
        }
        
        return view('teacher.daily-reports.index', compact('circles', 'selectedCircle', 'students', 'date', 'surahs'));
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

    /**
     * Handle bulk report creation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkStore(Request $request)
    {
        $user = Auth::user();
        $teacherCircles = StudyCircle::where('teacher_id', $user->id)->pluck('id');
        
        // Get all students in teacher's circles
        $studentsInCircles = DB::table('circle_students')
            ->whereIn('circle_id', $teacherCircles)
            ->pluck('student_id');

        $request->validate([
            'reports' => 'required|array',
            'reports.*.student_id' => [
                'required',
                'exists:users,id',
                Rule::in($studentsInCircles), // Ensure students belong to teacher's circles
            ],
            'reports.*.report_date' => 'required|date',
            'reports.*.memorization_parts' => 'required|numeric|min:0.25|max:30',
            'reports.*.revision_parts' => 'required|numeric|min:0|max:30',
            'reports.*.grade' => 'required|numeric|min:0|max:100',
            'reports.*.memorization_from_surah' => 'required|exists:surahs,id',
            'reports.*.memorization_from_verse' => 'required|integer|min:1',
            'reports.*.memorization_to_surah' => 'required|exists:surahs,id',
            'reports.*.memorization_to_verse' => 'required|integer|min:1',
            'reports.*.notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->reports as $reportData) {
                // Check if report already exists for this student and date
                $existingReport = DailyReport::where('student_id', $reportData['student_id'])
                    ->where('report_date', $reportData['report_date'])
                    ->first();

                if ($existingReport) {
                    // Update existing report
                    $existingReport->update($reportData);
                } else {
                    // Create new report
                    DailyReport::create($reportData);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', t('reports_created_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', t('error_creating_reports') . ': ' . $e->getMessage());
        }
    }
} 