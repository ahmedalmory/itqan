<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\TaskCompletion;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin,department_admin');
    }

    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Task::with(['creator', 'department', 'assignments.assignedUser', 'assignments.completion']);
        
        // Filter by department for department admins
        if ($user->role === 'department_admin') {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $query->whereIn('department_id', $departmentIds);
        }
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $tasks = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get departments for filter
        $departments = $user->role === 'super_admin' 
            ? Department::all() 
            : $user->adminDepartments;
        
        return view('admin.tasks.index', compact('tasks', 'departments'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $user = Auth::user();
        $departments = $user->role === 'super_admin' 
            ? Department::all() 
            : $user->adminDepartments;
            
        return view('admin.tasks.create', compact('departments'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date|after_or_equal:today',
            'department_id' => 'nullable|exists:departments,id',
            'assignment_type' => 'required|in:user,role',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'assigned_to_role' => 'nullable|in:department_admin,teacher,supervisor',
            'is_recurring' => 'boolean',
            'recurring_days' => 'nullable|array',
            'recurring_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'excluded_dates' => 'nullable|array',
            'excluded_dates.*' => 'date',
        ]);
        
        // Validate department permission for department admins
        if ($user->role === 'department_admin' && $request->department_id) {
            if (!$user->adminDepartments()->where('departments.id', $request->department_id)->exists()) {
                return redirect()->back()->with('error', t('You do not have permission to create tasks for this department'));
            }
        }
        
        // Create task
        $taskData = $request->only([
            'title', 'description', 'priority', 'due_date', 'department_id'
        ]);
        
        $taskData['created_by'] = $user->id;
        
        if ($request->is_recurring) {
            $taskData['is_recurring'] = true;
            $taskData['recurring_type'] = 'weekly';
            
            // Set working days
            $days = $request->recurring_days ?? [];
            foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
                $taskData[$day] = in_array($day, $days);
            }
            
            if ($request->excluded_dates) {
                $taskData['excluded_dates'] = $request->excluded_dates;
            }
        }
        
        $task = Task::create($taskData);
        
        // Create initial assignment
        $this->createTaskAssignment($task, $request);
        
        return redirect()->route('admin.tasks.index')
            ->with('success', t('Task created successfully'));
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        $task->load(['creator', 'department', 'assignments.assignedUser', 'assignments.completion.completedBy']);
        
        $todaysAssignments = $task->assignments()
            ->forToday()
            ->with(['assignedUser', 'completion.completedBy'])
            ->get();
        
        $recentCompletions = $task->completions()
            ->with(['completedBy', 'assignment.assignedUser'])
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.tasks.show', compact('task', 'todaysAssignments', 'recentCompletions'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        
        $user = Auth::user();
        $departments = $user->role === 'super_admin' 
            ? Department::all() 
            : $user->adminDepartments;
            
        return view('admin.tasks.edit', compact('task', 'departments'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:active,inactive,completed,cancelled',
            'due_date' => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
            'is_recurring' => 'boolean',
            'recurring_days' => 'nullable|array',
            'recurring_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'excluded_dates' => 'nullable|array',
            'excluded_dates.*' => 'date',
        ]);
        
        $taskData = $request->only([
            'title', 'description', 'priority', 'status', 'due_date', 'department_id'
        ]);
        
        if ($request->is_recurring) {
            $taskData['is_recurring'] = true;
            $taskData['recurring_type'] = 'weekly';
            
            // Set working days
            $days = $request->recurring_days ?? [];
            foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
                $taskData[$day] = in_array($day, $days);
            }
            
            if ($request->excluded_dates) {
                $taskData['excluded_dates'] = $request->excluded_dates;
            }
        } else {
            $taskData['is_recurring'] = false;
            $taskData['recurring_type'] = null;
            
            // Clear working days
            foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
                $taskData[$day] = false;
            }
            
            $taskData['excluded_dates'] = null;
        }
        
        $task->update($taskData);
        
        return redirect()->route('admin.tasks.show', $task)
            ->with('success', t('Task updated successfully'));
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();
        
        return redirect()->route('admin.tasks.index')
            ->with('success', t('Task deleted successfully'));
    }



    /**
     * Get tasks statistics.
     */
    public function statistics()
    {
        $user = Auth::user();
        
        $query = Task::query();
        
        // Filter by department for department admins
        if ($user->role === 'department_admin') {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            $query->whereIn('department_id', $departmentIds);
        }
        
        $totalTasks = $query->count();
        $activeTasks = $query->where('status', 'active')->count();
        $completedTasks = $query->where('status', 'completed')->count();
        
        // Today's assignments
        $todaysAssignments = TaskAssignment::whereHas('task', function($q) use ($user) {
            if ($user->role === 'department_admin') {
                $departmentIds = $user->adminDepartments()->pluck('departments.id');
                $q->whereIn('department_id', $departmentIds);
            }
        })->forToday()->count();
        
        $todaysCompletions = TaskCompletion::whereHas('assignment.task', function($q) use ($user) {
            if ($user->role === 'department_admin') {
                $departmentIds = $user->adminDepartments()->pluck('departments.id');
                $q->whereIn('department_id', $departmentIds);
            }
        })->forToday()->count();
        
        return response()->json([
            'total_tasks' => $totalTasks,
            'active_tasks' => $activeTasks,
            'completed_tasks' => $completedTasks,
            'todays_assignments' => $todaysAssignments,
            'todays_completions' => $todaysCompletions,
            'completion_rate' => $todaysAssignments > 0 ? round(($todaysCompletions / $todaysAssignments) * 100, 2) : 0,
        ]);
    }

    /**
     * Get users for a specific department (API endpoint).
     */
    public function getDepartmentUsers(Department $department)
    {
        try {
            // Simple version - just get all users for now to test the endpoint
            $users = User::whereIn('role', ['teacher', 'supervisor', 'department_admin'])
                ->select('id', 'name', 'role')
                ->get()
                ->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'role' => $user->role
                    ];
                });
            
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Error in getDepartmentUsers: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }

    public function getAllUsers()
    {
        try {
            $users = User::whereIn('role', ['teacher', 'supervisor', 'department_admin'])
                ->select('id', 'name', 'role')
                ->get()
                ->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'role' => $user->role
                    ];
                });
            
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Error in getAllUsers: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create task assignment based on request data.
     */
    private function createTaskAssignment(Task $task, Request $request)
    {
        $assignmentData = [
            'task_id' => $task->id,
            'assignment_type' => $request->assignment_type,
            'assigned_date' => $request->due_date ?? today(),
            'due_date' => $request->due_date ?? today(),
            'status' => 'pending',
        ];
        
        if ($request->assignment_type === 'user') {
            $assignmentData['assigned_to_user_id'] = $request->assigned_to_user_id;
        } else {
            $assignmentData['assigned_to_role'] = $request->assigned_to_role;
        }
        
        TaskAssignment::create($assignmentData);
    }
} 