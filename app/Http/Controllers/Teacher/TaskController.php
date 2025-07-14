<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\TaskCompletion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher');
    }

    /**
     * Display a listing of the teacher's assigned tasks.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = TaskAssignment::forUser($user)
            ->with(['task.creator', 'task.department', 'completion'])
            ->orderBy('assigned_date', 'desc');
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->whereHas('task', function($q) use ($request) {
                $q->where('priority', $request->priority);
            });
        }
        
        if ($request->filled('date')) {
            $query->whereDate('assigned_date', $request->date);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('task', function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $assignments = $query->paginate(15);
        
        return view('teacher.tasks.index', compact('assignments'));
    }

    /**
     * Display today's tasks.
     */
    public function today(Request $request)
    {
        $user = Auth::user();
        $date = $request->date ? Carbon::parse($request->date) : today();
        
        // Get today's assignments (both real and virtual)
        $assignments = $this->getTodaysAssignments($user, $date);
        
        return view('teacher.tasks.today', compact('assignments', 'date'));
    }

    /**
     * Display the specified task assignment.
     */
    public function show(TaskAssignment $assignment)
    {
        $user = Auth::user();
        
        if (!$assignment->canBeCompletedBy($user)) {
            abort(403, 'Unauthorized');
        }
        
        $assignment->load(['task.creator', 'task.department', 'completion.completedBy']);
        
        return view('teacher.tasks.show', compact('assignment'));
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
     * Mark task assignment as completed (handles both real and virtual assignments).
     */
    public function completeTask(Request $request)
    {
        $user = Auth::user();
        $date = $request->date ? Carbon::parse($request->date) : today();
        
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $task = Task::findOrFail($request->task_id);
        
        // Check if user can complete this task
        if (!$this->canUserCompleteTask($user, $task)) {
            return redirect()->back()->with('error', t('You are not authorized to complete this task'));
        }
        
        // Find or create assignment for this task and date
        $assignment = TaskAssignment::firstOrCreate([
            'task_id' => $task->id,
            'assigned_to_role' => $user->role,
            'assigned_date' => $date,
        ], [
            'assignment_type' => 'role',
            'due_date' => $date,
            'status' => 'pending',
        ]);
        
        $assignment->markAsCompleted($user, $request->notes);
        
        return redirect()->back()->with('success', t('Task marked as completed'));
    }

    /**
     * Mark task assignment as not completed (handles both real and virtual assignments).
     */
    public function uncompleteTask(Request $request)
    {
        $user = Auth::user();
        $date = $request->date ? Carbon::parse($request->date) : today();
        
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
        ]);
        
        $task = Task::findOrFail($request->task_id);
        
        // Find the assignment for this task and date
        $assignment = TaskAssignment::where([
            'task_id' => $task->id,
            'assigned_to_role' => $user->role,
            'assigned_date' => $date,
        ])->first();
        
        if (!$assignment) {
            return redirect()->back()->with('error', t('Task assignment not found'));
        }
        
        if (!$assignment->canBeCompletedBy($user)) {
            return redirect()->back()->with('error', t('You are not authorized to modify this task'));
        }
        
        $assignment->markAsNotCompleted();
        
        return redirect()->back()->with('success', t('Task marked as not completed'));
    }

    /**
     * Bulk complete multiple tasks.
     */
    public function bulkComplete(Request $request)
    {
        $user = Auth::user();
        $date = $request->date ? Carbon::parse($request->date) : today();
        
        $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:tasks,id',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $tasks = Task::whereIn('id', $request->task_ids)->get();
        $completed = 0;
        
        foreach ($tasks as $task) {
            if ($this->canUserCompleteTask($user, $task)) {
                // Find or create assignment for this task and date
                $assignment = TaskAssignment::firstOrCreate([
                    'task_id' => $task->id,
                    'assigned_to_role' => $user->role,
                    'assigned_date' => $date,
                ], [
                    'assignment_type' => 'role',
                    'due_date' => $date,
                    'status' => 'pending',
                ]);
                
                $assignment->markAsCompleted($user, $request->notes);
                $completed++;
            }
        }
        
        return redirect()->back()->with('success', t('Marked :count tasks as completed', ['count' => $completed]));
    }

    /**
     * Get completion report for sharing.
     */
    public function getCompletionReport(Request $request)
    {
        $user = Auth::user();
        $date = $request->date ? Carbon::parse($request->date) : today();
        
        // Get assignments using the same logic as today() method
        $assignments = $this->getTodaysAssignments($user, $date);
        
        $completedTasks = $assignments->filter(function($assignment) {
            return $assignment->isCompleted();
        });
        
        $pendingTasks = $assignments->filter(function($assignment) {
            return !$assignment->isCompleted();
        });
        
        $report = [
            'date' => $date->format('Y-m-d'),
            'date_formatted' => $date->format('l, F j, Y'),
            'user_name' => $user->name,
            'total_tasks' => $assignments->count(),
            'completed_tasks' => $completedTasks->count(),
            'pending_tasks' => $pendingTasks->count(),
            'completion_rate' => $assignments->count() > 0 ? round(($completedTasks->count() / $assignments->count()) * 100, 2) : 0,
            'completed_list' => $completedTasks->map(function($assignment) {
                return [
                    'title' => $assignment->task->title,
                    'completed_at' => $assignment->completion ? $assignment->completion->completed_at->format('H:i') : null,
                    'notes' => $assignment->completion ? $assignment->completion->notes : null,
                ];
            }),
            'pending_list' => $pendingTasks->map(function($assignment) {
                return [
                    'title' => $assignment->task->title,
                    'priority' => $assignment->task->priority,
                ];
            }),
        ];
        
        return response()->json($report);
    }

    /**
     * Generate shareable text for WhatsApp.
     */
    public function generateShareText(Request $request)
    {
        $user = Auth::user();
        $date = $request->date ? Carbon::parse($request->date) : today();
        
        // Get assignments using the same logic as today() method
        $assignments = $this->getTodaysAssignments($user, $date);
        
        $completedTasks = $assignments->filter(function($assignment) {
            return $assignment->isCompleted();
        });
        
        $pendingTasks = $assignments->filter(function($assignment) {
            return !$assignment->isCompleted();
        });
        
        $text = "📋 " . t('Daily Tasks Report') . "\n";
        $text .= "📅 " . $date->format('l, F j, Y') . "\n";
        $text .= "👤 " . $user->name . "\n\n";
        
        $text .= "📊 " . t('Summary') . ":\n";
        $text .= "• " . t('Total Tasks') . ": " . $assignments->count() . "\n";
        $text .= "• " . t('Completed') . ": " . $completedTasks->count() . "\n";
        $text .= "• " . t('Pending') . ": " . $pendingTasks->count() . "\n";
        
        if ($assignments->count() > 0) {
            $completionRate = round(($completedTasks->count() / $assignments->count()) * 100, 2);
            $text .= "• " . t('Completion Rate') . ": " . $completionRate . "%\n\n";
        }
        
        if ($completedTasks->count() > 0) {
            $text .= "✅ " . t('Completed Tasks') . ":\n";
            foreach ($completedTasks as $assignment) {
                $text .= "• " . $assignment->task->title;
                if ($assignment->completion && $assignment->completion->completed_at) {
                    $text .= " (" . $assignment->completion->completed_at->format('H:i') . ")";
                }
                $text .= "\n";
            }
            $text .= "\n";
        }
        
        if ($pendingTasks->count() > 0) {
            $text .= "⏳ " . t('Pending Tasks') . ":\n";
            foreach ($pendingTasks as $assignment) {
                $text .= "• " . $assignment->task->title;
                if ($assignment->task->priority === 'urgent') {
                    $text .= " ⚠️";
                } elseif ($assignment->task->priority === 'high') {
                    $text .= " 🔴";
                }
                $text .= "\n";
            }
        }
        
        return response()->json(['text' => $text]);
    }

    /**
     * Get tasks statistics for teacher.
     */
    public function statistics()
    {
        $user = Auth::user();
        
        $totalAssignments = TaskAssignment::forUser($user)->count();
        $completedAssignments = TaskAssignment::forUser($user)->completed()->count();
        $pendingAssignments = TaskAssignment::forUser($user)->pending()->count();
        $overdueAssignments = TaskAssignment::forUser($user)->overdue()->count();
        
        // Today's tasks
        $todaysTasks = TaskAssignment::forUser($user)->forToday()->count();
        $todaysCompleted = TaskAssignment::forUser($user)->forToday()->completed()->count();
        
        // This week's tasks
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $weeklyTasks = TaskAssignment::forUser($user)
            ->whereBetween('assigned_date', [$weekStart, $weekEnd])
            ->count();
        $weeklyCompleted = TaskAssignment::forUser($user)
            ->whereBetween('assigned_date', [$weekStart, $weekEnd])
            ->completed()
            ->count();
        
        return response()->json([
            'total_assignments' => $totalAssignments,
            'completed_assignments' => $completedAssignments,
            'pending_assignments' => $pendingAssignments,
            'overdue_assignments' => $overdueAssignments,
            'todays_tasks' => $todaysTasks,
            'todays_completed' => $todaysCompleted,
            'todays_completion_rate' => $todaysTasks > 0 ? round(($todaysCompleted / $todaysTasks) * 100, 2) : 0,
            'weekly_tasks' => $weeklyTasks,
            'weekly_completed' => $weeklyCompleted,
            'weekly_completion_rate' => $weeklyTasks > 0 ? round(($weeklyCompleted / $weeklyTasks) * 100, 2) : 0,
            'overall_completion_rate' => $totalAssignments > 0 ? round(($completedAssignments / $totalAssignments) * 100, 2) : 0,
        ]);
    }

    /**
     * Get today's assignments (both real and virtual) for a user.
     */
    private function getTodaysAssignments(User $user, Carbon $date)
    {
        // Get existing assignments for this date
        $existingAssignments = TaskAssignment::forUser($user)
            ->forDate($date)
            ->with(['task.creator', 'task.department', 'completion'])
            ->get();
        
        // Get recurring tasks that should be shown today but don't have assignments yet
        $recurringTasks = Task::active()
            ->recurring()
            ->whereHas('assignments', function($query) use ($user) {
                $query->forUser($user);
            })
            ->orWhereDoesntHave('assignments', function($query) use ($user) {
                $query->forUser($user);
            })
            ->get()
            ->filter(function($task) use ($date) {
                return $task->shouldAssignOnDate($date);
            });
        
        // Create virtual assignments for recurring tasks that don't have real assignments for today
        $virtualAssignments = collect();
        foreach ($recurringTasks as $task) {
            // Check if we already have a real assignment for this task today
            $hasRealAssignment = $existingAssignments->contains(function($assignment) use ($task) {
                return $assignment->task_id == $task->id;
            });
            
            if (!$hasRealAssignment) {
                // Create a virtual assignment
                $virtualAssignment = new TaskAssignment([
                    'task_id' => $task->id,
                    'assignment_type' => 'role',
                    'assigned_to_role' => $user->role,
                    'assigned_date' => $date,
                    'due_date' => $date,
                    'status' => 'pending',
                ]);
                $virtualAssignment->setRelation('task', $task);
                $virtualAssignment->is_virtual = true;
                $virtualAssignments->push($virtualAssignment);
            }
        }
        
        // Combine real and virtual assignments
        return $existingAssignments->merge($virtualAssignments)->sortBy('task_id');
    }

    /**
     * Check if user can complete a task.
     */
    private function canUserCompleteTask(User $user, Task $task)
    {
        // Create a virtual assignment to check permissions
        $virtualAssignment = new TaskAssignment([
            'assignment_type' => 'role',
            'assigned_to_role' => $user->role,
        ]);
        $virtualAssignment->setRelation('task', $task);
        
        return $virtualAssignment->canBeCompletedBy($user);
    }
} 