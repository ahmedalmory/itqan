<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Circle\EnrollStudentRequest;
use App\Http\Requests\Circle\StoreCircleRequest;
use App\Http\Requests\Circle\UpdateCircleRequest;
use App\Models\Department;
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
        $this->middleware('role:super_admin,department_admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = StudyCircle::with(['department', 'teacher', 'supervisor']);
        
        // Department admins can only see circles in their departments
        if ($user->isDepartmentAdmin()) {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $query->whereIn('department_id', $departmentIds);
        }
        
        // Filter by department if provided
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }
        
        $circles = $query->paginate(15);
        $departments = Department::all();
        
        return view('admin.circles.index', compact('circles', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $departments = Department::all();
        
        // Department admins can only see their departments
        if ($user->isDepartmentAdmin()) {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $departments = Department::whereIn('id', $departmentIds)->get();
        }
        
        // Get teachers and supervisors for dropdown
        $teachers = User::where('role', 'teacher')->where('is_active', true)->get();
        $supervisors = User::where('role', 'supervisor')->where('is_active', true)->get();
        
        return view('admin.circles.create', compact('departments', 'teachers', 'supervisors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCircleRequest $request)
    {
        try {
            $circle = $this->circleService->createCircle($request->validated());
            
            return redirect()->route('admin.circles.show', $circle)
                ->with('success', 'Study circle created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create study circle: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StudyCircle $circle)
    {
        $circle->load(['department', 'teacher', 'supervisor', 'students']);
        
        // Get students for this circle
        $students = $circle->students()->paginate(15);
        
        return view('admin.circles.show', compact('circle', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudyCircle $circle)
    {
        $user = Auth::user();
        $departments = Department::all();
        
        // Department admins can only see their departments
        if ($user->isDepartmentAdmin()) {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $departments = Department::whereIn('id', $departmentIds)->get();
            
            // Check if the circle belongs to one of admin's departments
            if (!$departmentIds->contains($circle->department_id)) {
                return abort(403, 'Unauthorized action.');
            }
        }
        
        // Get teachers and supervisors for dropdown
        $teachers = User::where('role', 'teacher')->where('is_active', true)->get();
        $supervisors = User::where('role', 'supervisor')->where('is_active', true)->get();
        
        return view('admin.circles.edit', compact('circle', 'departments', 'teachers', 'supervisors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCircleRequest $request, StudyCircle $circle)
    {
        try {
            $circle = $this->circleService->updateCircle($circle, $request->validated());
            
            return redirect()->route('admin.circles.show', $circle)
                ->with('success', 'Study circle updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update study circle: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudyCircle $circle)
    {
        try {
            // Check if circle has students
            if ($circle->students()->exists()) {
                return back()->with('error', 'Cannot delete circle with enrolled students.');
            }
            
            $circle->delete();
            
            return redirect()->route('admin.circles.index')
                ->with('success', 'Study circle deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete study circle: ' . $e->getMessage());
        }
    }
    
    /**
     * Show form to add a student to the circle.
     */
    public function showAddStudent(StudyCircle $circle)
    {
        // Get students who are not already in the circle
        $students = User::where('role', 'student')
            ->where('gender', $circle->department->student_gender)
            ->where('age', '>=', $circle->age_from)
            ->where('age', '<=', $circle->age_to)
            ->whereDoesntHave('circles', function ($query) use ($circle) {
                $query->where('study_circles.id', $circle->id);
            })
            ->get();
            
        return view('admin.circles.add-student', compact('circle', 'students'));
    }
    
    /**
     * Add a student to the circle.
     */
    public function addStudent(EnrollStudentRequest $request, StudyCircle $circle)
    {
        try {
            $this->circleService->enrollStudent($circle->id, $request->student_id);
            
            return redirect()->route('admin.circles.show', $circle)
                ->with('success', 'Student added to circle successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to add student to circle: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove a student from the circle.
     */
    public function removeStudent(StudyCircle $circle, User $student)
    {
        try {
            $this->circleService->removeStudent($circle->id, $student->id);
            
            return redirect()->route('admin.circles.show', $circle)
                ->with('success', 'Student removed from circle successfully.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to remove student from circle: ' . $e->getMessage());
        }
    }
} 