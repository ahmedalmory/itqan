<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCompletion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_assignment_id',
        'completed_by',
        'completed_at',
        'notes',
        'attachments',
        'is_completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'attachments' => 'json',
        'is_completed' => 'boolean',
    ];

    /**
     * Get the task assignment that this completion belongs to.
     */
    public function assignment()
    {
        return $this->belongsTo(TaskAssignment::class, 'task_assignment_id');
    }

    /**
     * Get the user who completed the task.
     */
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Get the task through the assignment.
     */
    public function task()
    {
        return $this->hasOneThrough(Task::class, TaskAssignment::class, 'id', 'id', 'task_assignment_id', 'task_id');
    }

    /**
     * Scope a query to only include completions for today.
     */
    public function scopeForToday($query)
    {
        return $query->whereDate('completed_at', today());
    }

    /**
     * Scope a query to only include completions for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('completed_at', $date);
    }

    /**
     * Scope a query to only include completions by a specific user.
     */
    public function scopeByUser($query, User $user)
    {
        return $query->where('completed_by', $user->id);
    }

    /**
     * Get formatted completion date.
     */
    public function getFormattedCompletedAtAttribute()
    {
        return $this->completed_at->format('Y-m-d H:i');
    }

    /**
     * Get formatted completion date for display.
     */
    public function getCompletedAtForHumansAttribute()
    {
        return $this->completed_at->diffForHumans();
    }
} 