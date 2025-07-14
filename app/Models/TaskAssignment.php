<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TaskAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'assignment_type',
        'assigned_to_user_id',
        'assigned_to_role',
        'assigned_date',
        'due_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'assigned_date' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Get the task that this assignment belongs to.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user that this task is assigned to.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Get the task completion for this assignment.
     */
    public function completion()
    {
        return $this->hasOne(TaskCompletion::class);
    }

    /**
     * Get the display name for this assignment (either user name or role name).
     */
    public function getAssignedDisplayName()
    {
        if ($this->assignment_type === 'user' && $this->assignedUser) {
            return $this->assignedUser->name;
        }
        
        if ($this->assignment_type === 'role') {
            return match($this->assigned_to_role) {
                'teacher' => t('Teachers'),
                'supervisor' => t('Supervisors'),
                'department_admin' => t('Department Admins'),
                'student' => t('Students'),
                default => ucfirst($this->assigned_to_role),
            };
        }
        
        return t('Unknown');
    }

    /**
     * Check if the assignment is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the assignment is overdue.
     */
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && !$this->isCompleted();
    }

    /**
     * Mark assignment as completed.
     */
    public function markAsCompleted(User $user, $notes = null)
    {
        $this->update(['status' => 'completed']);
        
        return $this->completion()->create([
            'completed_by' => $user->id,
            'completed_at' => now(),
            'notes' => $notes,
            'is_completed' => true,
        ]);
    }

    /**
     * Mark assignment as not completed.
     */
    public function markAsNotCompleted()
    {
        $this->update(['status' => 'pending']);
        $this->completion()->delete();
    }

    /**
     * Get users who can complete this assignment.
     */
    public function getEligibleUsers()
    {
        if ($this->assignment_type === 'user') {
            return User::where('id', $this->assigned_to_user_id)->get();
        }

        // For role assignments, get users with that role
        $query = User::where('role', $this->assigned_to_role);
        
        // If task has department, filter by department
        if ($this->task->department_id) {
            switch ($this->assigned_to_role) {
                case 'department_admin':
                    $query->whereHas('adminDepartments', function($q) {
                        $q->where('departments.id', $this->task->department_id);
                    });
                    break;
                case 'teacher':
                    $query->whereHas('taughtCircles', function($q) {
                        $q->where('department_id', $this->task->department_id);
                    });
                    break;
                case 'supervisor':
                    $query->whereHas('supervisedCircles', function($q) {
                        $q->where('department_id', $this->task->department_id);
                    });
                    break;
            }
        }

        return $query->get();
    }

    /**
     * Check if a user can complete this assignment.
     */
    public function canBeCompletedBy(User $user)
    {
        return $this->getEligibleUsers()->contains('id', $user->id);
    }

    /**
     * Scope a query to only include assignments for a specific user.
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where(function($q) use ($user) {
            $q->where('assigned_to_user_id', $user->id)
              ->orWhere('assigned_to_role', $user->role);
        });
    }

    /**
     * Scope a query to only include pending assignments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed assignments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include overdue assignments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
                     ->where('status', '!=', 'completed');
    }

    /**
     * Scope a query to only include assignments for today.
     */
    public function scopeForToday($query)
    {
        return $query->whereDate('assigned_date', today());
    }

    /**
     * Scope a query to only include assignments for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('assigned_date', $date);
    }

    /**
     * Get status color for display.
     */
    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'primary',
            'completed' => 'success',
            'overdue' => 'danger',
            'cancelled' => 'secondary',
            default => 'secondary',
        };
    }
} 