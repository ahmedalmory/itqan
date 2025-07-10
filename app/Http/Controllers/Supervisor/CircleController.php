<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\StudyCircle;
use App\Models\User;
use App\Services\CircleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CircleController extends Controller
{
    /**
     * @var CircleService
     */
    protected $circleService;

    /**
     * Create a new controller instance.
     *
     * @param CircleService $circleService
     * @return void
     */
    public function __construct(CircleService $circleService)
    {
        $this->circleService = $circleService;
        $this->middleware('auth');
        $this->middleware('role:supervisor');
    }

    /**
     * Display a listing of the supervised circles.
     */
    public function index()
    {
        $user = Auth::user();
        $circles = $user->supervisedCircles()->with(['department', 'teacher'])->get();
        
        return view('supervisor.circles.index', compact('circles'));
    }

    /**
     * Display the specified circle details.
     */
    public function show(StudyCircle $circle)
    {
        $user = Auth::user();
        
        // Ensure the supervisor can only view their own circles
        if ($circle->supervisor_id !== $user->id) {
            return abort(403, 'You do not have permission to view this circle.');
        }
        
        $circle->load(['department', 'teacher', 'students']);
        
        // Get students for this circle with their points
        $students = $circle->students()
            ->with(['studentPoints' => function ($query) use ($circle) {
                $query->where('circle_id', $circle->id);
            }])
            ->paginate(15);
            
        return view('supervisor.circles.show', compact('circle', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudyCircle $circle)
    {
        $user = Auth::user();
        
        // Ensure the supervisor can only edit their own circles
        if ($circle->supervisor_id !== $user->id) {
            return abort(403, 'You do not have permission to edit this circle.');
        }
        
        $teachers = User::where('role', 'teacher')->where('is_active', true)->get();
        
        return view('supervisor.circles.edit', compact('circle', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudyCircle $circle)
    {
        $user = Auth::user();
        
        // Ensure the supervisor can only update their own circles
        if ($circle->supervisor_id !== $user->id) {
            return abort(403, 'You do not have permission to update this circle.');
        }
        
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'teacher_id' => 'sometimes|nullable|exists:users,id,role,teacher',
            'whatsapp_group' => 'sometimes|nullable|string|max:255',
            'telegram_group' => 'sometimes|nullable|string|max:255',
            'circle_time' => 'sometimes|nullable|string|max:255',
        ]);
        
        try {
            $this->circleService->updateCircle($circle, $validatedData);
            
            return redirect()->route('supervisor.circles.show', $circle)
                ->with('success', 'Study circle updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update study circle: ' . $e->getMessage());
        }
    }
    
    /**
     * Manage students in the circle.
     */
    public function manageStudents(StudyCircle $circle)
    {
        $user = Auth::user();
        
        // Ensure the supervisor can only manage their own circles
        if ($circle->supervisor_id !== $user->id) {
            return abort(403, 'You do not have permission to manage students in this circle.');
        }
        
        $circle->load(['students']);
        $currentStudents = $circle->students;
        
        // Get potential students to add
        $potentialStudents = User::where('role', 'student')
            ->where('is_active', true)
            ->whereNotIn('id', $currentStudents->pluck('id'))
            ->get();
        
        return view('supervisor.circles.manage-students', compact('circle', 'currentStudents', 'potentialStudents'));
    }
    
    /**
     * Add a student to the circle.
     */
    public function addStudent(Request $request, StudyCircle $circle)
    {
        $user = Auth::user();
        
        // Ensure the supervisor can only manage their own circles
        if ($circle->supervisor_id !== $user->id) {
            return abort(403, 'You do not have permission to add students to this circle.');
        }
        
        $validatedData = $request->validate([
            'student_id' => 'required|exists:users,id,role,student',
        ]);
        
        try {
            // Check if the circle has reached maximum capacity
            if ($circle->max_students && $circle->students()->count() >= $circle->max_students) {
                return back()->with('error', 'The circle has reached its maximum capacity.');
            }
            
            // Check if the student is already in the circle
            if ($circle->students()->where('users.id', $validatedData['student_id'])->exists()) {
                return back()->with('error', 'The student is already enrolled in this circle.');
            }
            
            $circle->students()->attach($validatedData['student_id']);
            
            return back()->with('success', 'Student added to circle successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add student: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove a student from the circle.
     */
    public function removeStudent(StudyCircle $circle, User $student)
    {
        $user = Auth::user();
        
        // Ensure the supervisor can only manage their own circles
        if ($circle->supervisor_id !== $user->id) {
            return abort(403, 'You do not have permission to remove students from this circle.');
        }
        
        try {
            // Check if the student is in the circle
            if (!$circle->students()->where('users.id', $student->id)->exists()) {
                return back()->with('error', 'The student is not enrolled in this circle.');
            }
            
            $circle->students()->detach($student->id);
            
            return back()->with('success', 'Student removed from circle successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove student: ' . $e->getMessage());
        }
    }
    
    /**
     * View student details and performance.
     */
    public function viewStudent(StudyCircle $circle, User $student)
    {
        $user = Auth::user();
        
        // Ensure the supervisor can only view students in their own circles
        if ($circle->supervisor_id !== $user->id) {
            return abort(403, 'You do not have permission to view this student.');
        }
        
        // Check if the student is in the circle
        if (!$circle->students()->where('users.id', $student->id)->exists()) {
            return abort(404, 'The student is not enrolled in this circle.');
        }
        
        // Get student points and daily reports
        $points = $student->studentPoints()->where('circle_id', $circle->id)->first();
        $reports = $student->dailyReports()
            ->with(['fromSurah', 'toSurah', 'revision_from_surah', 'revision_to_surah'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('supervisor.circles.student', compact('circle', 'student', 'points', 'reports'));
    }
} 