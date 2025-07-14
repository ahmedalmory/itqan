<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['super_admin', 'department_admin', 'teacher', 'supervisor']);
    }

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task)
    {
        // Super admin can view all tasks
        if ($user->role === 'super_admin') {
            return true;
        }
        
        // Department admin can view tasks in their departments
        if ($user->role === 'department_admin') {
            return $user->adminDepartments()->where('departments.id', $task->department_id)->exists();
        }
        
        // Task creator can view their own tasks
        if ($task->created_by === $user->id) {
            return true;
        }
        
        // Users can view tasks assigned to them
        $hasAssignment = $task->assignments()->where(function($query) use ($user) {
            $query->where('assigned_to_user_id', $user->id)
                  ->orWhere('assigned_to_role', $user->role);
        })->exists();
        
        return $hasAssignment;
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user)
    {
        return in_array($user->role, ['super_admin', 'department_admin']);
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Task $task)
    {
        // Super admin can update all tasks
        if ($user->role === 'super_admin') {
            return true;
        }
        
        // Department admin can update tasks in their departments
        if ($user->role === 'department_admin') {
            return $user->adminDepartments()->where('departments.id', $task->department_id)->exists();
        }
        
        // Task creator can update their own tasks
        return $task->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Task $task)
    {
        // Super admin can delete all tasks
        if ($user->role === 'super_admin') {
            return true;
        }
        
        // Department admin can delete tasks in their departments
        if ($user->role === 'department_admin') {
            return $user->adminDepartments()->where('departments.id', $task->department_id)->exists();
        }
        
        // Task creator can delete their own tasks
        return $task->created_by === $user->id;
    }

    /**
     * Determine whether the user can create tasks for a specific department.
     */
    public function createForDepartment(User $user, $departmentId)
    {
        // Super admin can create tasks for any department
        if ($user->role === 'super_admin') {
            return true;
        }
        
        // Department admin can create tasks for their departments
        if ($user->role === 'department_admin') {
            return $user->adminDepartments()->where('departments.id', $departmentId)->exists();
        }
        
        return false;
    }

    /**
     * Determine whether the user can assign tasks to a specific role.
     */
    public function assignToRole(User $user, $role)
    {
        // Super admin can assign to any role
        if ($user->role === 'super_admin') {
            return true;
        }
        
        // Department admin can assign to teachers and supervisors
        if ($user->role === 'department_admin') {
            return in_array($role, ['teacher', 'supervisor']);
        }
        
        return false;
    }

    /**
     * Determine whether the user can assign tasks to a specific user.
     */
    public function assignToUser(User $user, User $targetUser)
    {
        // Super admin can assign to anyone
        if ($user->role === 'super_admin') {
            return true;
        }
        
        // Department admin can assign to users in their departments
        if ($user->role === 'department_admin') {
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            
            // Check if target user is in the same department
            switch ($targetUser->role) {
                case 'department_admin':
                    return $targetUser->adminDepartments()->whereIn('departments.id', $departmentIds)->exists();
                case 'teacher':
                    return $targetUser->taughtCircles()->whereIn('department_id', $departmentIds)->exists();
                case 'supervisor':
                    return $targetUser->supervisedCircles()->whereIn('department_id', $departmentIds)->exists();
                default:
                    return false;
            }
        }
        
        return false;
    }
} 