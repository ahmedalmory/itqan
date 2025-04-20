<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Circle\EnrollStudentRequest;
use App\Models\Department;
use App\Models\StudyCircle;
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
        $this->middleware('role:student');
    }

    /**
     * Display a listing of the student's circles.
     */
    public function index()
    {
        $user = Auth::user();
        $circles = $user->circles()->with(['department', 'teacher', 'supervisor'])->get();
        
        return view('student.circles.index', compact('circles'));
    }

    /**
     * Display the specified circle details.
     */
    public function show(StudyCircle $circle)
    {
        $user = Auth::user();
        
        // Check if student is enrolled in this circle
        if (!$user->circles()->where('study_circles.id', $circle->id)->exists()) {
            return abort(403, 'You are not enrolled in this circle.');
        }
        
        $circle->load(['department', 'teacher', 'supervisor']);
        
        // Get classmates (other students in the circle)
        $classmates = $circle->students()
            ->where('users.id', '!=', $user->id)
            ->get();
            
        // Get student points
        $points = $user->studentPoints()
            ->where('circle_id', $circle->id)
            ->first();
            
        return view('student.circles.show', compact('circle', 'classmates', 'points'));
    }

    /**
     * Show available circles for enrollment.
     */
    public function browseCircles(Request $request)
    {
        $user = Auth::user();
        
        // Get departments for filter
        $departments = Department::where('student_gender', $user->gender)
            ->where('registration_open', true)
            ->get();
            
        $query = StudyCircle::with(['department', 'teacher'])
            ->whereHas('department', function ($q) use ($user) {
                $q->where('student_gender', $user->gender)
                    ->where('registration_open', true);
            })
            ->where('age_from', '<=', $user->age)
            ->where('age_to', '>=', $user->age);
            
        // Apply department filter if provided
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }
        
        // Apply time filter if provided
        if ($request->has('circle_time') && $request->circle_time) {
            $query->where('circle_time', $request->circle_time);
        }
        
        // Exclude circles the student is already enrolled in
        $query->whereDoesntHave('students', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
        
        $availableCircles = $query->get();
        
        return view('student.circles.browse', compact('availableCircles', 'departments'));
    }

    /**
     * Enroll in a circle.
     */
    public function enroll(EnrollStudentRequest $request)
    {
        $user = Auth::user();
        
        try {
            $this->circleService->enrollStudent($request->circle_id, $user->id);
            
            return redirect()->route('student.circles.index')
                ->with('success', 'You have successfully enrolled in the circle.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to enroll in circle: ' . $e->getMessage());
        }
    }
} 