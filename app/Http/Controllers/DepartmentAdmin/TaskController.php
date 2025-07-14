<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\TaskCompletion;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:department_admin');
    }

    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $departmentIds = $user->adminDepartments()->pluck('departments.id');
        
        $query = Task::with(['creator', 'department', 'assignments.assignedUser', 'assignments.completion'])
            ->whereIn('department_id', $departmentIds);
        
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
        
        $departments = $user->adminDepartments;
        
        return view('department-admin.tasks.index', compact('tasks', 'departments'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $user = Auth::user();
        $departments = $user->adminDepartments;
        
        // Get teachers and supervisors in the managed departments
        $teachers = User::where('role', 'teacher')
            ->whereHas('taughtCircles', function($q) use ($user) {
                $departmentIds = $user->adminDepartments()->pluck('departments.id');
                $q->whereIn('department_id', $departmentIds);
            })
            ->get();
            
        $supervisors = User::where('role', 'supervisor')
            ->whereHas('supervisedCircles', function($q) use ($user) {
                $departmentIds = $user->adminDepartments()->pluck('departments.id');
                $q->whereIn('department_id', $departmentIds);
            })
            ->get();
        
        return view('department-admin.tasks.create', compact('departments', 'teachers', 'supervisors'));
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
            'department_id' => 'required|exists:departments,id',
            'assignment_type' => 'required|in:user,role',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'assigned_to_role' => 'nullable|in:teacher,supervisor',
            'is_recurring' => 'boolean',
            'recurring_days' => 'nullable|array',
            'recurring_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'excluded_dates' => 'nullable|array',
            'excluded_dates.*' => 'date',
        ]);
        
        // Validate department permission
        if (!$user->adminDepartments()->where('departments.id', $request->department_id)->exists()) {
            return redirect()->back()->with('error', t('You do not have permission to create tasks for this department'));
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
        
        return redirect()->route('department-admin.tasks.index')
            ->with('success', t('Task created successfully'));
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Check if user has permission to view this task
        if (!$user->adminDepartments()->where('departments.id', $task->department_id)->exists()) {
            abort(403, 'Unauthorized');
        }
        
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
        
        return view('department-admin.tasks.show', compact('task', 'todaysAssignments', 'recentCompletions'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $user = Auth::user();
        
        // Check if user has permission to edit this task
        if (!$user->adminDepartments()->where('departments.id', $task->department_id)->exists()) {
            abort(403, 'Unauthorized');
        }
        
        $departments = $user->adminDepartments;
        
        // Get teachers and supervisors in the managed departments
        $teachers = User::where('role', 'teacher')
            ->whereHas('taughtCircles', function($q) use ($user) {
                $departmentIds = $user->adminDepartments()->pluck('departments.id');
                $q->whereIn('department_id', $departmentIds);
            })
            ->get();
            
        $supervisors = User::where('role', 'supervisor')
            ->whereHas('supervisedCircles', function($q) use ($user) {
                $departmentIds = $user->adminDepartments()->pluck('departments.id');
                $q->whereIn('department_id', $departmentIds);
            })
            ->get();
        
        return view('department-admin.tasks.edit', compact('task', 'departments', 'teachers', 'supervisors'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Check if user has permission to update this task
        if (!$user->adminDepartments()->where('departments.id', $task->department_id)->exists()) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:active,inactive,completed,cancelled',
            'due_date' => 'nullable|date',
            'department_id' => 'required|exists:departments,id',
            'is_recurring' => 'boolean',
            'recurring_days' => 'nullable|array',
            'recurring_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'excluded_dates' => 'nullable|array',
            'excluded_dates.*' => 'date',
        ]);
        
        // Validate department permission
        if (!$user->adminDepartments()->where('departments.id', $request->department_id)->exists()) {
            return redirect()->back()->with('error', t('You do not have permission to assign tasks to this department'));
        }
        
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
        
        return redirect()->route('department-admin.tasks.show', $task)
            ->with('success', t('Task updated successfully'));
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        // Check if user has permission to delete this task
        if (!$user->adminDepartments()->where('departments.id', $task->department_id)->exists()) {
            abort(403, 'Unauthorized');
        }
        
        $task->delete();
        
        return redirect()->route('department-admin.tasks.index')
            ->with('success', t('Task deleted successfully'));
    }



    /**
     * Get my task assignments (as department admin).
     */
    public function myTasks(Request $request)
    {
        $user = Auth::user();
        
        $query = TaskAssignment::forUser($user)
            ->with(['task', 'completion'])
            ->orderBy('assigned_date', 'desc');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('assigned_date', $request->date);
        }
        
        $assignments = $query->paginate(15);
        
        return view('department-admin.tasks.my-tasks', compact('assignments'));
    }

    /**
     * Mark task assignment as completed.
     */
    public function complete(TaskAssignment $assignment, Request $request)
    {
        $user = Auth::user();
        
        if (!$assignment->canBeCompletedBy($user)) {
            return redirect()->back()->with('error', t('You are not authorized to complete this task'));
        }
        
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $assignment->markAsCompleted($user, $request->notes);
        
        return redirect()->back()->with('success', t('Task marked as completed'));
    }

    /**
     * Mark task assignment as not completed.
     */
    public function uncomplete(TaskAssignment $assignment)
    {
        $user = Auth::user();
        
        if (!$assignment->canBeCompletedBy($user)) {
            return redirect()->back()->with('error', t('You are not authorized to modify this task'));
        }
        
        $assignment->markAsNotCompleted();
        
        return redirect()->back()->with('success', t('Task marked as not completed'));
    }

    /**
     * Get tasks statistics for department admin.
     */
    public function statistics()
    {
        $user = Auth::user();
        $departmentIds = $user->adminDepartments()->pluck('departments.id');
        
        $totalTasks = Task::whereIn('department_id', $departmentIds)->count();
        $activeTasks = Task::whereIn('department_id', $departmentIds)->where('status', 'active')->count();
        $completedTasks = Task::whereIn('department_id', $departmentIds)->where('status', 'completed')->count();
        
        // Today's assignments
        $todaysAssignments = TaskAssignment::whereHas('task', function($q) use ($departmentIds) {
            $q->whereIn('department_id', $departmentIds);
        })->forToday()->count();
        
        $todaysCompletions = TaskCompletion::whereHas('assignment.task', function($q) use ($departmentIds) {
            $q->whereIn('department_id', $departmentIds);
        })->forToday()->count();
        
        // My tasks
        $myTodaysTasks = TaskAssignment::forUser($user)->forToday()->count();
        $myCompletedToday = TaskAssignment::forUser($user)->forToday()->completed()->count();
        
        return response()->json([
            'total_tasks' => $totalTasks,
            'active_tasks' => $activeTasks,
            'completed_tasks' => $completedTasks,
            'todays_assignments' => $todaysAssignments,
            'todays_completions' => $todaysCompletions,
            'completion_rate' => $todaysAssignments > 0 ? round(($todaysCompletions / $todaysAssignments) * 100, 2) : 0,
            'my_todays_tasks' => $myTodaysTasks,
            'my_completed_today' => $myCompletedToday,
            'my_completion_rate' => $myTodaysTasks > 0 ? round(($myCompletedToday / $myTodaysTasks) * 100, 2) : 0,
        ]);
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