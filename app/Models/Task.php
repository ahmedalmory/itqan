<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'is_recurring',
        'recurring_type',
        'recurring_config',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'excluded_dates',
        'created_by',
        'department_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'due_date' => 'date',
        'is_recurring' => 'boolean',
        'recurring_config' => 'json',
        'excluded_dates' => 'json',
        'monday' => 'boolean',
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
        'sunday' => 'boolean',
    ];

    /**
     * Get the user who created the task.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the department that the task belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the task assignments for this task.
     */
    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    /**
     * Get the task completions through assignments.
     */
    public function completions()
    {
        return $this->hasManyThrough(TaskCompletion::class, TaskAssignment::class);
    }

    /**
     * Check if the task is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the task is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Get the working days for this task.
     */
    public function getWorkingDays()
    {
        $days = [];
        if ($this->monday) $days[] = 'monday';
        if ($this->tuesday) $days[] = 'tuesday';
        if ($this->wednesday) $days[] = 'wednesday';
        if ($this->thursday) $days[] = 'thursday';
        if ($this->friday) $days[] = 'friday';
        if ($this->saturday) $days[] = 'saturday';
        if ($this->sunday) $days[] = 'sunday';
        return $days;
    }

    /**
     * Check if the task should be assigned on a specific date.
     */
    public function shouldAssignOnDate(Carbon $date)
    {
        if (!$this->is_recurring) {
            return $this->due_date && $this->due_date->isSameDay($date);
        }

        // Check if date is excluded
        if ($this->excluded_dates && in_array($date->toDateString(), $this->excluded_dates)) {
            return false;
        }

        // Check working days
        $dayName = strtolower($date->format('l'));
        return $this->{$dayName} ?? false;
    }

    /**
     * Generate assignments for a specific date range.
     */
    public function generateAssignments(Carbon $startDate, Carbon $endDate)
    {
        $assignments = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            if ($this->shouldAssignOnDate($currentDate)) {
                $assignments[] = [
                    'task_id' => $this->id,
                    'assigned_date' => $currentDate->toDateString(),
                    'due_date' => $currentDate->toDateString(),
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $currentDate->addDay();
        }

        return $assignments;
    }

    /**
     * Scope a query to only include active tasks.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include tasks for a specific department.
     */
    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope a query to only include recurring tasks.
     */
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    /**
     * Get priority color for display.
     */
    public function getPriorityColor()
    {
        return match($this->priority) {
            'low' => 'success',
            'medium' => 'primary',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get status color for display.
     */
    public function getStatusColor()
    {
        return match($this->status) {
            'active' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            'inactive' => 'secondary',
            default => 'secondary',
        };
    }
} 