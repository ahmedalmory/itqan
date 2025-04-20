<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\StudyCircle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();
        $user = auth()->user();
        
        // Department admins can't see super_admin or other department_admins
        if ($user->isDepartmentAdmin()) {
            $query->where(function($q) {
                $q->where('role', '!=', 'super_admin')
                  ->where(function($subQ) {
                      $subQ->where('role', '!=', 'department_admin')
                           ->orWhere('id', auth()->id()); // They can see themselves
                  });
            });
            
            // Department admins can only see users in their departments
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            
            // Filter users based on their circles' departments
            $query->where(function($q) use ($departmentIds) {
                // Users associated with circles that belong to these departments
                $q->whereHas('circles', function($subQ) use ($departmentIds) {
                    $subQ->whereIn('department_id', $departmentIds);
                });
            });
        }
        
        // Apply filters
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $users = $query->with('department')->latest()->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        $studyCircles = StudyCircle::all();
        $circles = StudyCircle::all();
        
        return view('admin.users.create', compact('departments', 'studyCircles', 'circles'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:male,female',
            'age' => 'nullable|integer|min:0', // Added age validation
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,supervisor,teacher,student',
            'study_circle_id' => 'nullable|required_if:role,student|exists:study_circles,id',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['is_active'] = $request->has('is_active') ? true : false;
        
        User::create($validatedData);
        
        return redirect()->route('admin.users.index')
            ->with('success', t('User created successfully'));
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load(['department', 'studyCircle']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $departments = Department::all();
        $studyCircles = StudyCircle::all();
        
        return view('admin.users.edit', compact('user', 'departments', 'studyCircles'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|string|in:male,female',
            'age' => 'nullable|integer|min:0', // Added age validation
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,supervisor,teacher,student',
            'study_circle_id' => 'nullable|required_if:role,student|exists:study_circles,id',
            'is_active' => 'nullable|boolean',
        ]);
        
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
        
        $validatedData['is_active'] = $request->has('is_active') ? true : false;
        
        // Clear department and study circle if role doesn't require them
        if (!in_array($validatedData['role'], ['supervisor', 'teacher', 'student'])) {
            $validatedData['department_id'] = null;
        }
        
        if ($validatedData['role'] !== 'student') {
            $validatedData['study_circle_id'] = null;
        }
        
        $user->update($validatedData);
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', t('User updated successfully'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Prevent users from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', t('You cannot delete your own account'));
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', t('User deleted successfully'));
    }
} 