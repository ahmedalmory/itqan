<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudyCircle;
use App\Models\User;
use App\Models\StudentPoint;
use App\Models\PointsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PointsController extends Controller
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
     * Display a listing of students with their points.
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
                return redirect()->route('teacher.points.index', ['circle_id' => $circles->first()->id]);
            }
            
            return view('teacher.points.index', [
                'circles' => $circles,
                'students' => collect(),
                'selectedCircle' => null
            ]);
        }
        
        $selectedCircle = $circles->firstWhere('id', $selectedCircleId);
        
        // Get students in the selected circle with their points
        $students = User::whereHas('circles', function($query) use ($selectedCircleId) {
                $query->where('study_circles.id', $selectedCircleId);
            })
            ->with(['studentPoints' => function($query) use ($selectedCircleId) {
                $query->where('circle_id', $selectedCircleId);
            }])
            ->get();
            
        // Format the students collection to include points data
        $students = $students->map(function($student) use ($selectedCircleId) {
            $points = $student->studentPoints->where('circle_id', $selectedCircleId)->first();
            
            $student->current_points = $points ? $points->points : 0;
            $student->total_points = $points ? $points->total_points : 0;
            $student->points_id = $points ? $points->id : null;
            
            return $student;
        })->sortByDesc('total_points')->values();
        
        return view('teacher.points.index', [
            'circles' => $circles,
            'students' => $students,
            'selectedCircle' => $selectedCircle
        ]);
    }

    /**
     * Update points for a student.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'circle_id' => 'required|exists:study_circles,id',
            'student_id' => 'required|exists:users,id',
            'points' => 'required|integer',
            'reason' => 'required|string|max:255',
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
        
        // Find or create student points record
        $studentPoints = StudentPoint::firstOrCreate(
            [
                'student_id' => $request->student_id,
                'circle_id' => $request->circle_id,
            ],
            [
                'points' => 0,
                'total_points' => 0,
            ]
        );
        
        // Create points history record
        PointsHistory::create([
            'student_id' => $request->student_id,
            'circle_id' => $request->circle_id,
            'points' => $request->points,
            'reason' => $request->reason,
        ]);
        
        // Update student points
        $studentPoints->total_points += max(0, $request->points); // Only add positive points to total
        $studentPoints->save();
        
        return redirect()->back()
            ->with('success', t('points_updated_successfully'));
    }

    /**
     * View points history for a student.
     *
     * @param Request $request
     * @param int $studentId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function history(Request $request, $studentId)
    {
        $user = Auth::user();
        $teacherCircles = StudyCircle::where('teacher_id', $user->id)->pluck('id');
        
        // Verify student is in one of teacher's circles
        $studentInTeacherCircle = DB::table('circle_students')
            ->whereIn('circle_id', $teacherCircles)
            ->where('student_id', $studentId)
            ->exists();
            
        if (!$studentInTeacherCircle) {
            return redirect()->route('teacher.points.index')
                ->with('error', t('student_not_in_your_circles'));
        }
        
        $student = User::findOrFail($studentId);
        
        // Get student's circles (limited to teacher's circles)
        $circles = StudyCircle::whereIn('id', $teacherCircles)
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
            ->with(['circle']);
            
        if ($selectedCircleId) {
            $historyQuery->where('circle_id', $selectedCircleId);
        } else {
            $historyQuery->whereIn('circle_id', $teacherCircles);
        }
        
        // Apply date filters if any
        if ($request->has('from_date') && $request->from_date) {
            $historyQuery->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $historyQuery->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Get results with pagination
        $history = $historyQuery->orderBy('created_at', 'desc')->paginate(20);
        
        // Get current points for each circle
        $studentPoints = StudentPoint::where('student_id', $studentId)
            ->whereIn('circle_id', $teacherCircles)
            ->get()
            ->keyBy('circle_id');
        
        return view('teacher.points.history', [
            'student' => $student,
            'circles' => $circles,
            'selectedCircleId' => $selectedCircleId,
            'history' => $history,
            'studentPoints' => $studentPoints,
            'filters' => $request->only(['circle_id', 'from_date', 'to_date']),
        ]);
    }

    /**
     * Display the leaderboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function leaderboard(Request $request)
    {
        $user = Auth::user();
        $teacherCircles = StudyCircle::where('teacher_id', $user->id)->pluck('id');
        
        // Get selected circle
        $selectedCircleId = $request->input('circle_id');
        
        // Build query for student points
        $pointsQuery = StudentPoint::whereIn('circle_id', $teacherCircles)
            ->with(['student', 'circle']);
            
        if ($selectedCircleId) {
            $pointsQuery->where('circle_id', $selectedCircleId);
        }
        
        // Order by points
        $leaderboard = $pointsQuery->orderBy('total_points', 'desc')
            ->paginate(20);
            
        // Get all circles for filter
        $circles = StudyCircle::whereIn('id', $teacherCircles)->get();
        
        return view('teacher.points.leaderboard', [
            'leaderboard' => $leaderboard,
            'circles' => $circles,
            'selectedCircleId' => $selectedCircleId,
        ]);
    }
} 