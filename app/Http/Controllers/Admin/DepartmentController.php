<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin,department_admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $query = Department::withCount('circles', 'students');
        
        // Department admins can only see their departments
        if ($user->isDepartmentAdmin()) {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $query->whereIn('id', $departmentIds);
        }
        
        $departments = $query->orderBy('name')->paginate(15);
        
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string|max:1000',
            'student_gender' => 'required|in:male,female,mixed',
            'monthly_fees' => 'nullable|numeric|min:0',
            'quarterly_fees' => 'nullable|numeric|min:0',
            'biannual_fees' => 'nullable|numeric|min:0',
            'annual_fees' => 'nullable|numeric|min:0',
            'work_days' => 'required|array|min:1',
            'work_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'registration_open' => 'boolean',
        ]);
        
        try {
            DB::beginTransaction();
            
            $workDays = $request->input('work_days', []);
            
            $department = Department::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'student_gender' => $validated['student_gender'],
                'monthly_fees' => $validated['monthly_fees'],
                'quarterly_fees' => $validated['quarterly_fees'],
                'biannual_fees' => $validated['biannual_fees'],
                'annual_fees' => $validated['annual_fees'],
                'work_monday' => in_array('monday', $workDays),
                'work_tuesday' => in_array('tuesday', $workDays),
                'work_wednesday' => in_array('wednesday', $workDays),
                'work_thursday' => in_array('thursday', $workDays),
                'work_friday' => in_array('friday', $workDays),
                'work_saturday' => in_array('saturday', $workDays),
                'work_sunday' => in_array('sunday', $workDays),
                'registration_open' => $request->boolean('registration_open'),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.departments.index')
                ->with('success', t('Department created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', t('Error creating department') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        $user = Auth::user();
        
        // Department admins can only see their departments
        if ($user->isDepartmentAdmin()) {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            if (!$departmentIds->contains($department->id)) {
                return abort(403, 'Unauthorized action.');
            }
        }
        
        $department->load('circles');
        $circleStudentCounts = DB::table('circle_students')
            ->join('study_circles', 'circle_students.circle_id', '=', 'study_circles.id')
            ->where('study_circles.department_id', $department->id)
            ->select('circle_students.circle_id', DB::raw('count(*) as student_count'))
            ->groupBy('circle_students.circle_id')
            ->pluck('student_count', 'circle_id')
            ->toArray();
            
        return view('admin.departments.show', compact('department', 'circleStudentCounts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        $user = Auth::user();
        
        // Department admins can only edit their departments
        if ($user->isDepartmentAdmin()) {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            if (!$departmentIds->contains($department->id)) {
                return abort(403, 'Unauthorized action.');
            }
        }
        
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $user = Auth::user();
        
        // Department admins can only update their departments
        if ($user->isDepartmentAdmin()) {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            if (!$departmentIds->contains($department->id)) {
                return abort(403, 'Unauthorized action.');
            }
        }
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department->id)],
            'description' => 'nullable|string|max:1000',
            'student_gender' => 'required|in:male,female,mixed',
            'monthly_fees' => 'nullable|numeric|min:0',
            'quarterly_fees' => 'nullable|numeric|min:0',
            'biannual_fees' => 'nullable|numeric|min:0',
            'annual_fees' => 'nullable|numeric|min:0',
            'work_days' => 'required|array|min:1',
            'work_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'registration_open' => 'boolean',
        ]);
        
        try {
            DB::beginTransaction();
            
            $workDays = $request->input('work_days', []);
            
            $department->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'student_gender' => $validated['student_gender'],
                'monthly_fees' => $validated['monthly_fees'],
                'quarterly_fees' => $validated['quarterly_fees'],
                'biannual_fees' => $validated['biannual_fees'],
                'annual_fees' => $validated['annual_fees'],
                'work_monday' => in_array('monday', $workDays),
                'work_tuesday' => in_array('tuesday', $workDays),
                'work_wednesday' => in_array('wednesday', $workDays),
                'work_thursday' => in_array('thursday', $workDays),
                'work_friday' => in_array('friday', $workDays),
                'work_saturday' => in_array('saturday', $workDays),
                'work_sunday' => in_array('sunday', $workDays),
                'registration_open' => $request->boolean('registration_open'),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.departments.index')
                ->with('success', t('Department updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', t('Error updating department') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $user = Auth::user();
        
        // Department admins can only delete their departments
        if ($user->isDepartmentAdmin()) {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            if (!$departmentIds->contains($department->id)) {
                return abort(403, 'Unauthorized action.');
            }
        }
        
        // Check if department has circles
        if ($department->circles()->count() > 0) {
            return back()->with('error', t('Cannot delete department with associated circles'));
        }
        
        try {
            $department->delete();
            return redirect()->route('admin.departments.index')
                ->with('success', t('Department deleted successfully'));
        } catch (\Exception $e) {
            return back()->with('error', t('Error deleting department') . ': ' . $e->getMessage());
        }
    }
} 