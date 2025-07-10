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
            'memorization_from_surah' => 'nullable|exists:surahs,id',
            'memorization_from_verse' => 'nullable|integer|min:1',
            'memorization_to_surah' => 'nullable|exists:surahs,id',
            'memorization_to_verse' => 'nullable|integer|min:1',
            'memorization_parts' => 'nullable|numeric|min:0.25|max:30|required_without:revision_parts',
            'revision_from_surah' => 'nullable|exists:surahs,id',
            'revision_from_verse' => 'nullable|integer|min:1',
            'revision_to_surah' => 'nullable|exists:surahs,id',
            'revision_to_verse' => 'nullable|integer|min:1',
            'revision_parts' => 'nullable|numeric|min:0|max:30|required_without:memorization_parts',
            'grade' => 'nullable|integer|min:0|max:100',
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
                'revision_from_surah' => $request->revision_from_surah,
                'revision_from_verse' => $request->revision_from_verse,
                'revision_to_surah' => $request->revision_to_surah,
                'revision_to_verse' => $request->revision_to_verse,
                'revision_parts' => $request->revision_parts,
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
            'revision_from_surah' => $request->revision_from_surah,
            'revision_from_verse' => $request->revision_from_verse,
            'revision_to_surah' => $request->revision_to_surah,
            'revision_to_verse' => $request->revision_to_verse,
            'revision_parts' => $request->revision_parts,
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
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\JsonResponse
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
            ->with(['student', 'fromSurah', 'toSurah', 'revisionFromSurah', 'revisionToSurah']);
            
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
        
        // Check if this is a request for students summary
        if ($request->has('export') && $request->export === 'summary') {
            return $this->generateStudentsSummary($reportsQuery, $request);
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
     * Generate students summary for export.
     *
     * @param \Illuminate\Database\Eloquent\Builder $reportsQuery
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function generateStudentsSummary($reportsQuery, Request $request)
    {
        try {
            // Get all reports without pagination
            $allReports = $reportsQuery->get();
            
            // Calculate statistics
            $totalReports = $allReports->count();
            $totalStudents = $allReports->pluck('student_id')->unique()->count();
            $averageGrade = $allReports->avg('grade') ?? 0;
            
            // Get top students with their statistics
            $studentStats = $allReports->groupBy('student_id')->map(function ($studentReports) {
                $student = $studentReports->first()->student;
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'reports_count' => $studentReports->count(),
                    'average_grade' => round($studentReports->avg('grade') ?? 0, 1),
                    'total_memorization' => round($studentReports->sum('memorization_parts'), 2),
                    'total_revision' => round($studentReports->sum('revision_parts'), 2),
                    'latest_report_date' => $studentReports->max('report_date'),
                ];
            })->sortByDesc('average_grade')->take(10)->values();
            
            // Get circle statistics if circle filter is applied
            $circleStats = null;
            if ($request->has('circle_id') && $request->circle_id) {
                $circle = StudyCircle::find($request->circle_id);
                if ($circle) {
                    $circleStats = [
                        'circle_name' => $circle->name,
                        'circle_id' => $circle->id,
                        'department' => $circle->department->name ?? null,
                        'teacher' => $circle->teacher->name ?? null,
                    ];
                }
            }
            
            // Get date range info
            $dateRange = [
                'from' => $request->from_date,
                'to' => $request->to_date,
                'generated_at' => now()->toDateTimeString(),
            ];
            
            // Prepare response data
            $summaryData = [
                'success' => true,
                'stats' => [
                    'total_students' => $totalStudents,
                    'total_reports' => $totalReports,
                    'average_grade' => round($averageGrade, 1),
                ],
                'top_students' => $studentStats,
                'circle_stats' => $circleStats,
                'date_range' => $dateRange,
                'filters' => $request->only(['student_id', 'circle_id', 'from_date', 'to_date']),
            ];
            
            return response()->json($summaryData);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating students summary: ' . $e->getMessage()
            ], 500);
        }
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
            'reports.*.memorization_parts' => 'nullable|numeric|min:0.25|max:30|required_without:reports.*.revision_parts',
            'reports.*.revision_parts' => 'nullable|numeric|min:0|max:30|required_without:reports.*.memorization_parts',
            'reports.*.grade' => 'nullable|numeric|min:0|max:100',
            'reports.*.memorization_from_surah' => 'nullable|exists:surahs,id',
            'reports.*.memorization_from_verse' => 'nullable|integer|min:1',
            'reports.*.memorization_to_surah' => 'nullable|exists:surahs,id',
            'reports.*.memorization_to_verse' => 'nullable|integer|min:1',
            'reports.*.revision_from_surah' => 'nullable|exists:surahs,id',
            'reports.*.revision_from_verse' => 'nullable|integer|min:1',
            'reports.*.revision_to_surah' => 'nullable|exists:surahs,id',
            'reports.*.revision_to_verse' => 'nullable|integer|min:1',
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

    /**
     * Display calendar-based daily reports dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function calendar(Request $request)
    {
        $user = Auth::user();
        $circles = StudyCircle::where('teacher_id', $user->id)->get();
        $selectedCircle = null;
        $students = collect();
        $calendarData = [];
        $stats = [];
        
        // Get current month/year from request or default to current
        $currentMonth = $request->get('month', now()->month);
        $currentYear = $request->get('year', now()->year);
        
        if ($request->has('circle_id')) {
            $selectedCircle = $circles->firstWhere('id', $request->circle_id);
            if ($selectedCircle) {
                $students = $selectedCircle->students()->with(['dailyReports' => function($query) use ($currentMonth, $currentYear) {
                    $query->whereMonth('report_date', $currentMonth)
                          ->whereYear('report_date', $currentYear)
                          ->with(['fromSurah', 'toSurah', 'revisionFromSurah', 'revisionToSurah']);
                }])->get();
                
                // Prepare calendar data
                $calendarData = $this->prepareCalendarData($students, $currentMonth, $currentYear);
                
                // Calculate statistics
                $stats = $this->calculateCircleStats($selectedCircle, $currentMonth, $currentYear);
            }
        }
        
        // Get surahs for bulk form
        $surahs = Surah::orderBy('id')->get();
        
        return view('teacher.daily-reports.calendar', compact(
            'circles', 
            'selectedCircle', 
            'students', 
            'calendarData', 
            'stats', 
            'currentMonth', 
            'currentYear',
            'surahs'
        ));
    }

    /**
     * Prepare calendar data for display.
     *
     * @param Collection $students
     * @param int $month
     * @param int $year
     * @return array
     */
    private function prepareCalendarData($students, $month, $year)
    {
        $calendarData = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        foreach ($students as $student) {
            $studentData = [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'joining_date' => $student->created_at->format('Y-m-d'),
                'profile_photo' => $student->profile_photo,
                'days' => []
            ];
            
            // Initialize all days for the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $studentData['days'][$day] = [
                    'date' => $date,
                    'has_memorization' => false,
                    'has_revision' => false,
                    'report' => null,
                    'color_class' => 'red' // default: no report
                ];
            }
            
            // Fill in actual report data
            foreach ($student->dailyReports as $report) {
                $day = $report->report_date->day;
                if (isset($studentData['days'][$day])) {
                    $hasMemorization = $report->memorization_parts > 0;
                    $hasRevision = $report->revision_parts > 0;
                    
                    $colorClass = 'red'; // default
                    if ($hasMemorization && $hasRevision) {
                        $colorClass = 'green';
                    } elseif ($hasMemorization) {
                        $colorClass = 'blue';
                    } elseif ($hasRevision) {
                        $colorClass = 'yellow';
                    }
                    
                    // Format report data with surah names
                    $formattedReport = [
                        'id' => $report->id,
                        'memorization_parts' => $report->memorization_parts,
                        'revision_parts' => $report->revision_parts,
                        'grade' => $report->grade,
                        'memorization_from_surah' => $report->fromSurah ? $report->fromSurah->name : null,
                        'memorization_to_surah' => $report->toSurah ? $report->toSurah->name : null,
                        'memorization_from_verse' => $report->memorization_from_verse,
                        'memorization_to_verse' => $report->memorization_to_verse,
                        'revision_from_surah' => $report->revisionFromSurah ? $report->revisionFromSurah->name : null,
                        'revision_to_surah' => $report->revisionToSurah ? $report->revisionToSurah->name : null,
                        'revision_from_verse' => $report->revision_from_verse,
                        'revision_to_verse' => $report->revision_to_verse,
                        'notes' => $report->notes,
                        'report_date' => $report->report_date->format('Y-m-d'),
                    ];
                    
                    $studentData['days'][$day] = [
                        'date' => $report->report_date->format('Y-m-d'),
                        'has_memorization' => $hasMemorization,
                        'has_revision' => $hasRevision,
                        'report' => (object) $formattedReport,
                        'color_class' => $colorClass
                    ];
                }
            }
            
            $calendarData[] = $studentData;
        }
        
        return $calendarData;
    }

    /**
     * Calculate statistics for a circle.
     *
     * @param StudyCircle $circle
     * @param int $month
     * @param int $year
     * @return array
     */
    private function calculateCircleStats($circle, $month, $year)
    {
        $studentsInCircle = DB::table('circle_students')
            ->where('circle_id', $circle->id)
            ->pluck('student_id');
            
        $reportsQuery = DailyReport::whereIn('student_id', $studentsInCircle)
            ->whereMonth('report_date', $month)
            ->whereYear('report_date', $year);
            
        $totalReports = $reportsQuery->count();
        $totalStudents = $studentsInCircle->count();
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $possibleReports = $totalStudents * $daysInMonth;
        
        $attendancePercentage = $possibleReports > 0 ? ($totalReports / $possibleReports) * 100 : 0;
        
        $memorizationReports = $reportsQuery->where('memorization_parts', '>', 0)->count();
        $revisionReports = $reportsQuery->where('revision_parts', '>', 0)->count();
        $bothReports = $reportsQuery->where('memorization_parts', '>', 0)
            ->where('revision_parts', '>', 0)->count();
        
        $averageGrade = $reportsQuery->avg('grade') ?? 0;
        $totalMemorizationParts = $reportsQuery->sum('memorization_parts') ?? 0;
        $totalRevisionParts = $reportsQuery->sum('revision_parts') ?? 0;
        
        return [
            'total_students' => $totalStudents,
            'total_reports' => $totalReports,
            'attendance_percentage' => round($attendancePercentage, 1),
            'memorization_reports' => $memorizationReports,
            'revision_reports' => $revisionReports,
            'both_reports' => $bothReports,
            'average_grade' => round($averageGrade, 1),
            'total_memorization_parts' => $totalMemorizationParts,
            'total_revision_parts' => $totalRevisionParts,
        ];
    }

    /**
     * Get report details for a specific date and student (AJAX endpoint).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReportDetails(Request $request)
    {
        $studentId = $request->get('student_id');
        $date = $request->get('date');
        
                $report = DailyReport::where('student_id', $studentId)
             ->where('report_date', $date)
             ->with(['fromSurah', 'toSurah', 'revisionFromSurah', 'revisionToSurah'])
             ->first();
            
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'No report found']);
        }
        
        return response()->json([
            'success' => true,
            'report' => [
                'id' => $report->id,
                'memorization_parts' => $report->memorization_parts,
                'revision_parts' => $report->revision_parts,
                'grade' => $report->grade,
                'memorization_from_surah' => $report->fromSurah ? $report->fromSurah->name : null,
                'memorization_to_surah' => $report->toSurah ? $report->toSurah->name : null,
                'memorization_from_verse' => $report->memorization_from_verse,
                'memorization_to_verse' => $report->memorization_to_verse,
                'revision_from_surah' => $report->revisionFromSurah ? $report->revisionFromSurah->name : null,
                'revision_to_surah' => $report->revisionToSurah ? $report->revisionToSurah->name : null,
                'revision_from_verse' => $report->revision_from_verse,
                'revision_to_verse' => $report->revision_to_verse,
                'notes' => $report->notes,
                'report_date' => $report->report_date->format('Y-m-d'),
            ]
        ]);
    }
} 