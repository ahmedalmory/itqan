<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Surah;
use App\Models\StudyCircle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $reports = DailyReport::where('student_id', $user->id)
            ->orderBy('report_date', 'desc')
            ->paginate(10);
            
        return view('student.reports.index', compact('reports'));
    }
    
    /**
     * Show the form for creating a new report.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        $circles = $user->circles;
        
        if ($circles->isEmpty()) {
            return redirect()->route('student.dashboard')
                ->with('error', t('no_circles_joined'));
        }
        
        $surahs = Surah::orderBy('id')->get();
        
        // Check if student already has a report for today
        $existingReport = DailyReport::where('student_id', $user->id)
            ->where('report_date', now()->format('Y-m-d'))
            ->first();
            
        return view('student.reports.create', compact('circles', 'surahs', 'existingReport'));
    }
    
    /**
     * Store a newly created report in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_date' => 'required|date|before_or_equal:today',
            'memorization_parts' => 'nullable|numeric|min:0|max:30|required_without:revision_parts',
            'revision_parts' => 'nullable|numeric|min:0|max:30|required_without:memorization_parts',
            'memorization_from_surah_id' => 'nullable|exists:surahs,id',
            'memorization_from_verse' => 'nullable|integer|min:1',
            'memorization_to_surah_id' => 'nullable|exists:surahs,id',
            'memorization_to_verse' => 'nullable|integer|min:1',
            'grade' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);
        $user = Auth::user();
        
        // Check if student already has a report for this date
        $existingReport = DailyReport::where('student_id', $user->id)
            ->where('report_date', $validated['report_date'])
            ->first();
            
        if ($existingReport) {
            return redirect()->route('student.reports.edit', $existingReport)
                ->with('info', t('report_already_exists'));
        }
                
        try {
            DB::beginTransaction();
            
            // Create the daily report
            $report = DailyReport::create([
                'student_id' => $user->id,
                'report_date' => $validated['report_date'],
                'memorization_parts' => $validated['memorization_parts'],
                'revision_parts' => $validated['revision_parts'],
                'grade' => $validated['grade'],
                'memorization_from_surah' => $validated['memorization_from_surah_id'],
                'memorization_from_verse' => $validated['memorization_from_verse'],
                'memorization_to_surah' => $validated['memorization_to_surah_id'],
                'memorization_to_verse' => $validated['memorization_to_verse'],
                'notes' => $validated['notes'],
            ]);
            
            DB::commit();
            
            return redirect()->route('student.reports.index')
                ->with('success', t('report_created_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', t('error_creating_report') . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified report.
     *
     * @param  \App\Models\DailyReport  $report
     * @return \Illuminate\View\View
     */
    public function show(DailyReport $report)
    {
        // $this->authorize('view', $report);
        
        $fromSurah = Surah::find($report->memorization_from_surah);
        $toSurah = Surah::find($report->memorization_to_surah);
        
        return view('student.reports.show', compact('report', 'fromSurah', 'toSurah'));
    }
    
    /**
     * Show the form for editing the specified report.
     *
     * @param  \App\Models\DailyReport  $report
     * @return \Illuminate\View\View
     */
    public function edit(DailyReport $report)
    {
        // $this->authorize('update', $report);
        
        $user = Auth::user();
        $circles = $user->circles;
        $surahs = Surah::orderBy('id')->get();
        
        return view('student.reports.edit', compact('report', 'circles', 'surahs'));
    }
    
    /**
     * Update the specified report in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DailyReport  $report
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, DailyReport $report)
    {
        // $this->authorize('update', $report);
        
        $validated = $request->validate([
            'memorization_parts' => 'nullable|numeric|min:0|max:30|required_without:revision_parts',
            'revision_parts' => 'nullable|numeric|min:0|max:30|required_without:memorization_parts',
            'memorization_from_surah_id' => 'required|exists:surahs,id',
            'memorization_from_verse' => 'required|integer|min:1',
            'memorization_to_surah_id' => 'required|exists:surahs,id',
            'memorization_to_verse' => 'required|integer|min:1',
            'grade' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        
        try {
            $report->update($validated);
            
            return redirect()->route('student.reports.index')
                ->with('success', t('report_updated_successfully'));
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', t('error_updating_report') . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified report from storage.
     *
     * @param  \App\Models\DailyReport  $report
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DailyReport $report)
    {
        // $this->authorize('delete', $report);
        
        try {
            $report->delete();
            
            return redirect()->route('student.reports.index')
                ->with('success', t('report_deleted_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', t('error_deleting_report') . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Remove a report for a specific date.
     *
     * @param  string  $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyByDate($date)
    {
        $user = Auth::user();
        
        try {
            $report = DailyReport::where('student_id', $user->id)
                ->where('report_date', $date)
                ->firstOrFail();
                
            $this->authorize('delete', $report);
            $report->delete();
            
            return response()->json(['success' => true, 'message' => t('report_deleted_successfully')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => t('error_deleting_report')], 500);
        }
    }
} 