<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Http\Controllers\Teacher\PointsController as BasePointsController;
use App\Models\StudyCircle;
use App\Models\User;
use App\Models\StudentPoint;
use App\Models\PointsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PointsController extends BasePointsController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:department_admin');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get department IDs that this admin manages
        $departmentIds = $user->adminDepartments()->pluck('departments.id');
        
        $circles = StudyCircle::where(function($query) use ($departmentIds) {
                $query->whereIn('department_id', $departmentIds);
            })
            ->withCount('students')
            ->get();
            
        // Get selected circle
        $selectedCircleId = $request->input('circle_id', $circles->first()->id ?? null);
        
        if (!$selectedCircleId || !$circles->contains('id', $selectedCircleId)) {
            return view('department-admin.points.index', [
                'circles' => $circles,
                'students' => collect(),
                'selectedCircle' => null
            ]);
        }
        
        $selectedCircle = $circles->firstWhere('id', $selectedCircleId);
        
        // Get students in the selected circle with their points
        // Only get students from circles in admin's departments
        $students = User::whereHas('circles', function($query) use ($selectedCircleId, $departmentIds) {
                $query->where('study_circles.id', $selectedCircleId)
                      ->whereIn('department_id', $departmentIds);
            })
            ->with(['studentPoints' => function($query) use ($selectedCircleId) {
                $query->where('circle_id', $selectedCircleId);
            }])
            ->get();
            
        $students = $students->map(function($student) use ($selectedCircleId) {
            $points = $student->studentPoints->where('circle_id', $selectedCircleId)->first();
            $student->total_points = $points ? $points->total_points : 0;
            return $student;
        })->sortByDesc('total_points')->values();
        
        return view('department-admin.points.index', [
            'circles' => $circles,
            'students' => $students,
            'selectedCircle' => $selectedCircle
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $departmentIds = $user->adminDepartments()->pluck('departments.id');
        
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
        
        // Verify that the circle belongs to one of the admin's departments
        $circle = StudyCircle::where('id', $request->circle_id)
            ->whereIn('department_id', $departmentIds)
            ->first();

        if (!$circle) {
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
                'notes' => $request->reason ?? '',
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

    public function bulkUpdate(Request $request)
    {
        $user = Auth::user();
        $departmentIds = $user->adminDepartments()->pluck('departments.id');
        
        $validator = Validator::make($request->all(), [
            'circle_id' => 'required|exists:study_circles,id',
            'points' => 'required|integer',
            'reason' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Verify that the circle belongs to admin's departments
        $circle = StudyCircle::where('id', $request->circle_id)
            ->whereIn('department_id', $departmentIds)
            ->firstOrFail();
            
        // Get all students in the circle
        $students = User::whereHas('circles', function($query) use ($circle) {
                $query->where('study_circles.id', $circle->id);
            })->get();
            
        if ($students->isEmpty()) {
            return redirect()->back()
                ->with('error', t('no_students_in_circle'))
                ->withInput();
        }
        
        DB::beginTransaction();
        try {
            foreach ($students as $student) {
                $studentPoints = StudentPoint::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'circle_id' => $circle->id,
                    ],
                    [
                        'total_points' => 0,
                    ]
                );
                
                PointsHistory::create([
                    'student_id' => $student->id,
                    'circle_id' => $circle->id,
                    'points' => $request->points,
                    'action_type' => $request->points >= 0 ? 'add' : 'subtract',
                    'notes' => $request->reason ?? '',
                    'created_by' => $user->id,
                ]);
                
                $studentPoints->total_points += $request->points;
                $studentPoints->save();
            }
            
            DB::commit();
            return redirect()->back()
                ->with('success', t('points_updated_successfully_for_all_students'));
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
        $departmentIds = $user->adminDepartments()->pluck('departments.id');
        
        // Verify student belongs to one of the admin's departments through circles
        $student = User::whereHas('circles', function($query) use ($departmentIds) {
                $query->whereIn('department_id', $departmentIds);
            })
            ->findOrFail($studentId);
        
        // Get all circles in the departments that the student is in
        $circles = StudyCircle::whereIn('department_id', $departmentIds)
            ->whereHas('students', function($query) use ($studentId) {
                $query->where('users.id', $studentId);
            })
            ->get();
            
        // Get selected circle
        $selectedCircleId = $request->input('circle_id');
        if ($selectedCircleId && !$circles->contains('id', $selectedCircleId)) {
            $selectedCircleId = null;
        }
        
        // Build points history query
        $historyQuery = PointsHistory::where('student_id', $studentId)
            ->whereIn('circle_id', $circles->pluck('id'))
            ->with(['circle']);
            
        if ($selectedCircleId) {
            $historyQuery->where('circle_id', $selectedCircleId);
        }
        
        $history = $historyQuery->orderBy('created_at', 'desc')->get();
        
        return view('department-admin.points.history', [
            'student' => $student,
            'circles' => $circles,
            'selectedCircle' => $selectedCircleId ? $circles->firstWhere('id', $selectedCircleId) : null,
            'history' => $history
        ]);
    }

    public function leaderboard(Request $request)
    {
        $user = Auth::user();
        $departmentIds = $user->adminDepartments()->pluck('departments.id');
        
        // Get all circles in the admin's departments
        $circles = StudyCircle::whereIn('department_id', $departmentIds)
            ->withCount('students')
            ->get();
            
        // Get selected circle
        $selectedCircleId = $request->input('circle_id');
        if ($selectedCircleId && !$circles->contains('id', $selectedCircleId)) {
            $selectedCircleId = null;
        }
        
        // Build leaderboard query
        $query = User::whereHas('circles', function($query) use ($departmentIds, $selectedCircleId) {
                $query->whereIn('study_circles.department_id', $departmentIds);
                if ($selectedCircleId) {
                    $query->where('study_circles.id', $selectedCircleId);
                }
            })
            ->with(['studentPoints' => function($query) use ($selectedCircleId) {
                if ($selectedCircleId) {
                    $query->where('circle_id', $selectedCircleId);
                }
            }]);
            
        $students = $query->get()->map(function($student) use ($selectedCircleId) {
            if ($selectedCircleId) {
                $points = $student->studentPoints->where('circle_id', $selectedCircleId)->first();
                $student->total_points = $points ? $points->total_points : 0;
            } else {
                $student->total_points = $student->studentPoints->sum('total_points');
            }
            return $student;
        })->sortByDesc('total_points')->values();
        
        return view('department-admin.points.leaderboard', [
            'circles' => $circles,
            'selectedCircle' => $selectedCircleId ? $circles->firstWhere('id', $selectedCircleId) : null,
            'students' => $students
        ]);
    }
} 