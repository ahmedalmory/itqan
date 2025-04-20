<?php

namespace App\Policies;

use App\Models\StudentSubscription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentSubscriptionPolicy
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
     * @param  \App\Models\StudentSubscription  $subscription
     * @return bool
     */
    public function view(User $user, StudentSubscription $subscription)
    {
        // Students can only view their own subscriptions
        if ($user->role === 'student') {
            return $user->id === $subscription->student_id;
        }
        
        // Department admins can view subscriptions in their departments
        if ($user->role === 'department_admin') {
            return $user->adminDepartments()
                ->where('departments.id', $subscription->department_id)
                ->exists();
        }
        
        // Super admins can view all subscriptions
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
        // Only students can create subscriptions
        return $user->role === 'student';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StudentSubscription  $subscription
     * @return bool
     */
    public function update(User $user, StudentSubscription $subscription)
    {
        // Students can only update their own pending subscriptions
        if ($user->role === 'student') {
            return $user->id === $subscription->student_id && 
                  $subscription->status === 'pending';
        }
        
        // Department admins can update subscriptions in their departments
        if ($user->role === 'department_admin') {
            return $user->adminDepartments()
                ->where('departments.id', $subscription->department_id)
                ->exists();
        }
        
        // Super admins can update all subscriptions
        return $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StudentSubscription  $subscription
     * @return bool
     */
    public function delete(User $user, StudentSubscription $subscription)
    {
        // Students cannot delete subscriptions
        if ($user->role === 'student') {
            return false;
        }
        
        // Department admins can delete subscriptions in their departments
        if ($user->role === 'department_admin') {
            return $user->adminDepartments()
                ->where('departments.id', $subscription->department_id)
                ->exists();
        }
        
        // Super admins can delete all subscriptions
        return $user->role === 'super_admin';
    }
} 