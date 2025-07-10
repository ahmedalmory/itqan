<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Teacher\PointsController as BasePointsController;
use App\Models\StudyCircle;
use App\Models\User;
use App\Models\StudentPoint;
use App\Models\PointsHistory;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PointsController extends BasePointsController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin,department_admin');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get circles based on user role
        $circlesQuery = StudyCircle::withCount('students');
        
        if ($user->role === 'department_admin') {
            // Get department IDs that this admin manages
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $circlesQuery->whereIn('department_id', $departmentIds);
        }
        
        $circles = $circlesQuery->get();
            
        // Get selected circle
        $selectedCircleId = $request->input('circle_id', $circles->first()->id ?? null);
        
        if (!$selectedCircleId || !$circles->contains('id', $selectedCircleId)) {
            return view('admin.points.index', [
                'circles' => $circles,
                'students' => collect(),
                'selectedCircle' => null
            ]);
        }
        
        $selectedCircle = $circles->firstWhere('id', $selectedCircleId);
        
        // Get students in the selected circle with their points
        $studentsQuery = User::whereHas('circles', function($query) use ($selectedCircleId) {
            $query->where('study_circles.id', $selectedCircleId);
        });
        
        if ($user->role === 'department_admin') {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $studentsQuery->whereHas('circles', function($query) use ($departmentIds) {
                $query->whereIn('department_id', $departmentIds);
            });
        }
        
        $students = $studentsQuery->with(['studentPoints' => function($query) use ($selectedCircleId) {
                $query->where('circle_id', $selectedCircleId);
            }])
            ->get();
            
        $students = $students->map(function($student) use ($selectedCircleId) {
            $points = $student->studentPoints->where('circle_id', $selectedCircleId)->first();
            $student->total_points = $points ? $points->total_points : 0;
            return $student;
        })->sortByDesc('total_points')->values();
        
        return view('admin.points.index', [
            'circles' => $circles,
            'students' => $students,
            'selectedCircle' => $selectedCircle
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'circle_id' => 'required|exists:study_circles,id',
            'student_id' => 'required|exists:users,id',
            'points' => 'required|integer',
            'reason' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
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
        
        // For department admin, verify the circle belongs to their department
        if ($user->role === 'department_admin') {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $circleInDepartment = StudyCircle::where('id', $request->circle_id)
                ->whereIn('department_id', $departmentIds)
                ->exists();
                
            if (!$circleInDepartment) {
                return redirect()->back()
                    ->with('error', t('unauthorized_action'))
                    ->withInput();
            }
        }
        
        DB::beginTransaction();
        try {
            $studentPoints = StudentPoint::firstOrCreate(
                [
                    'student_id' => $request->student_id,
                    'circle_id' => $request->circle_id,
                ],
                [
                    'total_points' => 0,
                ]
            );
            
            PointsHistory::create([
                'student_id' => $request->student_id,
                'circle_id' => $request->circle_id,
                'points' => $request->points,
                'action_type' => $request->points >= 0 ? 'add' : 'subtract',
                'notes' => $request->reason,
                'created_by' => $user->id,
            ]);
            
            $studentPoints->total_points += $request->points;
            $studentPoints->save();
            
            DB::commit();
            return redirect()->back()
                ->with('success', t('points_updated_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', t('error_updating_points'))
                ->withInput();
        }
    }

    public function history(Request $request, $studentId)
    {
        $user = Auth::user();
        $student = User::findOrFail($studentId);
        
        // Get circles based on user role
        $circlesQuery = StudyCircle::whereHas('students', function($query) use ($studentId) {
            $query->where('users.id', $studentId);
        });
        
        if ($user->role === 'department_admin') {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $circlesQuery->whereIn('department_id', $departmentIds);
        }
        
        $circles = $circlesQuery->get();
            
        // Get selected circle
        $selectedCircleId = $request->input('circle_id');
        if ($selectedCircleId && !$circles->contains('id', $selectedCircleId)) {
            $selectedCircleId = null;
        }
        
        // Build points history query
        $historyQuery = PointsHistory::where('student_id', $studentId)
            ->with(['circle']);
            
        if ($selectedCircleId) {
            $historyQuery->where('circle_id', $selectedCircleId);
        }
        
        if ($user->role === 'department_admin') {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $historyQuery->whereHas('circle', function($query) use ($departmentIds) {
                $query->whereIn('department_id', $departmentIds);
            });
        }
        
        if ($request->has('from_date') && $request->from_date) {
            $historyQuery->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $historyQuery->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Check if this is a request for points summary
        if ($request->has('export') && $request->export === 'summary') {
            return $this->generatePointsSummary($historyQuery, $student, $circles, $request);
        }
        
        $history = $historyQuery->orderBy('created_at', 'desc')->paginate(20);
        
        $studentPoints = StudentPoint::where('student_id', $studentId);
        
        if ($user->role === 'department_admin') {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $studentPoints->whereHas('circle', function($query) use ($departmentIds) {
                $query->whereIn('department_id', $departmentIds);
            });
        }
        
        $studentPoints = $studentPoints->get()->keyBy('circle_id');
        
        return view('admin.points.history', [
            'student' => $student,
            'circles' => $circles,
            'selectedCircleId' => $selectedCircleId,
            'history' => $history,
            'studentPoints' => $studentPoints,
            'filters' => $request->only(['circle_id', 'from_date', 'to_date']),
        ]);
    }

    /**
     * Generate points summary for export.
     *
     * @param \Illuminate\Database\Eloquent\Builder $historyQuery
     * @param \App\Models\User $student
     * @param \Illuminate\Database\Eloquent\Collection $circles
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function generatePointsSummary($historyQuery, $student, $circles, Request $request)
    {
        try {
            // Get all history records without pagination
            $allHistory = $historyQuery->get();
            
            // Calculate statistics
            $totalPoints = $allHistory->sum('points');
            $totalHistoryRecords = $allHistory->count();
            
            // Get circles breakdown
            $circlesBreakdown = $allHistory->groupBy('circle_id')->map(function ($circleHistory) {
                $circle = $circleHistory->first()->circle;
                return [
                    'circle_id' => $circle->id,
                    'circle_name' => $circle->name,
                    'total_points' => $circleHistory->sum('points'),
                    'records_count' => $circleHistory->count(),
                    'last_activity' => $circleHistory->max('created_at'),
                ];
            })->values()->sortByDesc('total_points');
            
            // Get student info
            $studentInfo = [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'total_points' => $totalPoints,
            ];
            
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
                    'total_points' => $totalPoints,
                    'total_history_records' => $totalHistoryRecords,
                ],
                'circles_breakdown' => $circlesBreakdown,
                'student_info' => $studentInfo,
                'date_range' => $dateRange,
                'filters' => $request->only(['circle_id', 'from_date', 'to_date']),
            ];
            
            return response()->json($summaryData);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating points summary: ' . $e->getMessage()
            ], 500);
        }
    }

    public function leaderboard(Request $request)
    {
        $user = Auth::user();
        
        // Get selected department and circle
        $selectedDepartmentId = $request->input('department_id');
        $selectedCircleId = $request->input('circle_id');
        
        // Build query for student points
        $pointsQuery = StudentPoint::with(['student', 'circle.department']);
            
        if ($selectedCircleId) {
            $pointsQuery->where('circle_id', $selectedCircleId);
        }
        
        if ($selectedDepartmentId) {
            $pointsQuery->whereHas('circle', function($query) use ($selectedDepartmentId) {
                $query->where('department_id', $selectedDepartmentId);
            });
        }
        
        if ($user->role === 'department_admin') {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $pointsQuery->whereHas('circle', function($query) use ($departmentIds) {
                $query->whereIn('department_id', $departmentIds);
            });
        }
        
        // Order by points
        $leaderboard = $pointsQuery->orderBy('total_points', 'desc')
            ->paginate(20);
            
        // Get circles based on user role and selected department
        $circlesQuery = StudyCircle::query();
        
        if ($selectedDepartmentId) {
            $circlesQuery->where('department_id', $selectedDepartmentId);
        }
        
        if ($user->role === 'department_admin') {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $circlesQuery->whereIn('department_id', $departmentIds);
        }
        
        $circles = $circlesQuery->get();

        // Get departments based on user role
        if ($user->role === 'department_admin') {
            $departments = $user->adminDepartments;
        } else {
            $departments = Department::all();
        }
        
        return view('admin.points.leaderboard', [
            'leaderboard' => $leaderboard,
            'circles' => $circles,
            'selectedCircleId' => $selectedCircleId,
            'departments' => $departments,
            'selectedDepartment' => $selectedDepartmentId,
        ]);
    }
} 