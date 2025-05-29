<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CsvController;
use App\Models\DailyReport;
use App\Models\Department;
use App\Models\StudyCircle;
use App\Models\User;
use App\Models\Surah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = DB::table('daily_reports')
            ->join('users', 'daily_reports.student_id', '=', 'users.id')
            ->leftJoin('circle_students', 'users.id', '=', 'circle_student.student_id')
            ->leftJoin('study_circles', 'circle_student.circle_id', '=', 'study_circles.id')
            ->leftJoin('departments', 'study_circles.department_id', '=', 'departments.id')
            ->select(
                DB::raw('DATE_FORMAT(daily_reports.report_date, "%Y-%m") as month'),
                'departments.name as department_name',
                DB::raw('COUNT(DISTINCT users.id) as students_count'),
                DB::raw('COUNT(daily_reports.id) as reports_count'),
                DB::raw('AVG(daily_reports.grade) as average_grade'),
                DB::raw('SUM(daily_reports.memorization_parts) as total_memorization'),
                DB::raw('SUM(daily_reports.revision_parts) as total_revision')
            )
            ->groupBy('month', 'departments.name');
            
        // Filter by department if provided
        if ($request->has('department_id') && $request->department_id) {
            $query->where('departments.id', $request->department_id);
        }
        
        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->where('daily_reports.report_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('daily_reports.report_date', '<=', $request->date_to);
        }
        
        $reports = $query->orderBy('month', 'desc')->paginate(20);
        $departments = Department::orderBy('name')->get();
        
        return view('admin.reports.index', compact('reports', 'departments'));
    }
    
    /**
     * Display daily reports.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dailyReports(Request $request)
    {
        $query = DailyReport::with(['student', 'student.circles', 'student.circles.department'])
            ->orderBy('report_date', 'desc');
            
        // Filter by student if provided
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }
        
        // Filter by circle if provided
        if ($request->has('circle_id') && $request->circle_id) {
            $query->whereHas('student.circles', function($q) use ($request) {
                $q->where('study_circles.id', $request->circle_id);
            });
        }
        
        // Filter by department if provided
        if ($request->has('department_id') && $request->department_id) {
            $query->whereHas('student.circles.department', function($q) use ($request) {
                $q->where('departments.id', $request->department_id);
            });
        }
        
        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->where('report_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('report_date', '<=', $request->date_to);
        }
        
        $reports = $query->paginate(20);
        $students = User::where('role', 'student')->orderBy('name')->get();
        $circles = StudyCircle::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $surahs = Surah::orderBy('id')->get();
        
        return view('admin.reports.daily', compact('reports', 'students', 'circles', 'departments', 'surahs'));
    }
    
    /**
     * Export reports to CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = DailyReport::with(['student', 'student.circles', 'student.circles.department'])
            ->orderBy('report_date', 'desc');
            
        // Apply filters
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }
        
        if ($request->has('circle_id') && $request->circle_id) {
            $query->whereHas('student.circles', function($q) use ($request) {
                $q->where('study_circles.id', $request->circle_id);
            });
        }
        
        if ($request->has('department_id') && $request->department_id) {
            $query->whereHas('student.circles.department', function($q) use ($request) {
                $q->where('departments.id', $request->department_id);
            });
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->where('report_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('report_date', '<=', $request->date_to);
        }
        
        $reports = $query->get();
        
        $headers = [
            'Student Name',
            'Circle',
            'Department',
            'Report Date',
            'Memorization Parts',
            'Revision Parts',
            'Grade',
            'Notes'
        ];
        
        $data = $reports->map(function($report) {
            return [
                'Student Name' => $report->student ? $report->student->name : 'Unknown Student',
                'Circle' => $report->student && $report->student->circles->first() ? $report->student->circles->first()->name : 'N/A',
                'Department' => $report->student && $report->student->circles->first() && $report->student->circles->first()->department ? $report->student->circles->first()->department->name : 'N/A',
                'Report Date' => $report->report_date->format('Y-m-d'),
                'Memorization Parts' => $report->memorization_parts,
                'Revision Parts' => $report->revision_parts,
                'Grade' => $report->grade,
                'Notes' => $report->notes
            ];
        });

        return $this->exportToCsv($data, $headers, 'daily_reports_' . date('Y-m-d') . '.csv');
    }

    /**
     * Export daily reports to CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportDaily(Request $request)
    {
        $query = DailyReport::with([
            'student', 
            'student.circles', 
            'student.circles.department',
            'memorization_from_surah',
            'memorization_to_surah',
            'revision_from_surah',
            'revision_to_surah'
        ])->orderBy('report_date', 'desc');
            
        // Filter by student name if provided
        if ($request->has('student_name') && $request->student_name) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->student_name . '%');
            });
        }
        
        // Filter by circle if provided
        if ($request->has('circle_id') && $request->circle_id) {
            $query->whereHas('student.circles', function($q) use ($request) {
                $q->where('study_circles.id', $request->circle_id);
            });
        }
        
        // Filter by department if provided
        if ($request->has('department_id') && $request->department_id) {
            $query->whereHas('student.circles.department', function($q) use ($request) {
                $q->where('departments.id', $request->department_id);
            });
        }
        
        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->where('report_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('report_date', '<=', $request->date_to);
        }
        
        $reports = $query->get();
        
        // Generate CSV content
        $headers = [
            'ID',
            'Student Name',
            'Circle',
            'Department',
            'Report Date',
            'Memorization Parts',
            'Revision Parts',
            'Grade',
            'Memorization From',
            'Memorization To',
            'Revision From',
            'Revision To',
            'Notes',
            'Created At'
        ];
        
        $callback = function() use ($reports, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($reports as $report) {
                $circleName = $report->student && $report->student->circles->first() ? $report->student->circles->first()->name : 'N/A';
                $departmentName = $report->student && $report->student->circles->first() && $report->student->circles->first()->department ? $report->student->circles->first()->department->name : 'N/A';
                
                $memorizationFrom = $report->memorization_from_surah ? 
                    $report->memorization_from_surah->name . ' (' . $report->memorization_from_verse . ')' : 'N/A';
                    
                $memorizationTo = $report->memorization_to_surah ? 
                    $report->memorization_to_surah->name . ' (' . $report->memorization_to_verse . ')' : 'N/A';
                    
                $revisionFrom = $report->revision_from_surah ? 
                    $report->revision_from_surah->name . ' (' . $report->revision_from_verse . ')' : 'N/A';
                    
                $revisionTo = $report->revision_to_surah ? 
                    $report->revision_to_surah->name . ' (' . $report->revision_to_verse . ')' : 'N/A';
                
                $row = [
                    $report->id,
                    $report->student ? $report->student->name : 'Unknown Student',
                    $circleName,
                    $departmentName,
                    $report->report_date->format('Y-m-d'),
                    $report->memorization_parts,
                    $report->revision_parts,
                    $report->grade,
                    $memorizationFrom,
                    $memorizationTo,
                    $revisionFrom,
                    $revisionTo,
                    $report->notes,
                    $report->created_at->format('Y-m-d H:i:s')
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        $filename = 'daily_reports_' . date('Y-m-d_H-i-s') . '.csv';
        
        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Display the specified report.
     *
     * @param  \App\Models\DailyReport  $report
     * @return \Illuminate\Http\Response
     */
    public function show(DailyReport $report)
    {
        $report->load([
            'student', 
            'student.circles',
            'student.circles.department',
            'memorization_from_surah',
            'memorization_to_surah',
            'revision_from_surah',
            'revision_to_surah'
        ]);
        
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified report.
     *
     * @param  \App\Models\DailyReport  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(DailyReport $report)
    {
        $report->load([
            'student', 
            'memorization_from_surah',
            'memorization_to_surah',
            'revision_from_surah',
            'revision_to_surah'
        ]);
        
        $students = User::where('role', 'student')->orderBy('name')->get();
        $surahs = DB::table('surahs')->orderBy('id')->get();
        
        return view('admin.reports.edit', compact('report', 'students', 'surahs'));
    }

    /**
     * Update the specified report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DailyReport  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DailyReport $report)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'report_date' => 'required|date',
            'memorization_parts' => 'required|numeric|min:0|max:8',
            'revision_parts' => 'required|numeric|min:0|max:20',
            'memorization_from_surah_id' => 'nullable|exists:surahs,id',
            'memorization_from_verse' => 'nullable|numeric|min:1',
            'memorization_to_surah_id' => 'nullable|exists:surahs,id',
            'memorization_to_verse' => 'nullable|numeric|min:1',
            'revision_from_surah_id' => 'nullable|exists:surahs,id',
            'revision_from_verse' => 'nullable|numeric|min:1',
            'revision_to_surah_id' => 'nullable|exists:surahs,id',
            'revision_to_verse' => 'nullable|numeric|min:1',
            'grade' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        try {
            DB::transaction(function () use ($validated, $report) {
                $report->update($validated);
            });
            
            return redirect()->route('admin.reports.show', $report)
                ->with('success', t('Report updated successfully.'));
                
        } catch (\Exception $e) {
            return back()->withInput()->with('error', t('Failed to update report: ') . $e->getMessage());
        }
    }

    /**
     * Get required headers for CSV import
     */
    protected function getRequiredHeaders(): array
    {
        return [
            'Student Name',
            'Report Date',
            'Memorization Parts',
            'Revision Parts',
            'Grade',
            'Notes'
        ];
    }

    /**
     * Get column mapping for CSV import
     */
    protected function getColumnMap(): array
    {
        return [
            'Student Name' => 'student_name',
            'Report Date' => 'report_date',
            'Memorization Parts' => 'memorization_parts',
            'Revision Parts' => 'revision_parts',
            'Grade' => 'grade',
            'Notes' => 'notes'
        ];
    }

    /**
     * Process a single record from CSV
     */
    protected function processRecord(array $record)
    {
        // Find student by name
        $student = User::where('name', $record['student_name'])->first();
        
        if (!$student) {
            throw new \Exception("Student not found: {$record['student_name']}");
        }

        return DailyReport::create([
            'student_id' => $student->id,
            'report_date' => $record['report_date'],
            'memorization_parts' => $record['memorization_parts'],
            'revision_parts' => $record['revision_parts'],
            'grade' => $record['grade'],
            'notes' => $record['notes']
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
        $request->validate([
            'reports' => 'required|array',
            'reports.*.student_id' => 'required|exists:users,id',
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