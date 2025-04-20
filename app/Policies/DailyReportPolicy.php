<?php

namespace App\Policies;

use App\Models\DailyReport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DailyReport  $dailyReport
     * @return bool
     */
    public function view(User $user, DailyReport $dailyReport)
    {
        // Students can only view their own reports
        if ($user->role === 'student') {
            return $user->id === $dailyReport->student_id;
        }
        
        // Teachers can view reports of students in their circles
        if ($user->role === 'teacher') {
            return $user->teacherCircles()
                ->whereHas('students', function ($query) use ($dailyReport) {
                    $query->where('users.id', $dailyReport->student_id);
                })
                ->exists();
        }
        
        // Department admins can view reports of students in their department
        if ($user->role === 'department_admin') {
            return $user->adminDepartments()
                ->whereHas('circles', function ($query) use ($dailyReport) {
                    $query->whereHas('students', function ($subQuery) use ($dailyReport) {
                        $subQuery->where('users.id', $dailyReport->student_id);
                    });
                })
                ->exists();
        }
        
        // Super admins can view all reports
        return $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        // Only students can create reports
        return $user->role === 'student';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DailyReport  $dailyReport
     * @return bool
     */
    public function update(User $user, DailyReport $dailyReport)
    {
        // Students can only update their own reports if they're recent (within last 3 days)
        if ($user->role === 'student') {
            return $user->id === $dailyReport->student_id &&
                   $dailyReport->report_date >= now()->subDays(3)->format('Y-m-d');
        }
        
        // Teachers can update reports of students in their circles
        if ($user->role === 'teacher') {
            return $user->teacherCircles()
                ->whereHas('students', function ($query) use ($dailyReport) {
                    $query->where('users.id', $dailyReport->student_id);
                })
                ->exists();
        }
        
        // Department admins and super admins can update all reports
        return in_array($user->role, ['department_admin', 'super_admin']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DailyReport  $dailyReport
     * @return bool
     */
    public function delete(User $user, DailyReport $dailyReport)
    {
        // Students can only delete their own reports if they're recent (within last 1 day)
        if ($user->role === 'student') {
            return $user->id === $dailyReport->student_id &&
                   $dailyReport->report_date >= now()->subDays(1)->format('Y-m-d');
        }
        
        // Teachers can delete reports of students in their circles
        if ($user->role === 'teacher') {
            return $user->teacherCircles()
                ->whereHas('students', function ($query) use ($dailyReport) {
                    $query->where('users.id', $dailyReport->student_id);
                })
                ->exists();
        }
        
        // Department admins and super admins can delete all reports
        return in_array($user->role, ['department_admin', 'super_admin']);
    }
} 