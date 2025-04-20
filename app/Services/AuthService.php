<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function createUser(array $data): User
    {
        // Set default role if not provided
        if (!isset($data['role']) || empty($data['role'])) {
            $data['role'] = 'student';
        }

        // Set user as active by default
        if (!isset($data['is_active'])) {
            $data['is_active'] = true;
        }

        // Create the user
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'age' => $data['age'],
            'gender' => $data['gender'],
            'role' => $data['role'],
            'preferred_time' => $data['preferred_time'] ?? null,
            'country_id' => $data['country_id'] ?? null,
            'is_active' => $data['is_active'],
        ]);
    }

    /**
     * Check if a user can register for a specific department.
     *
     * @param \App\Models\User $user
     * @param int $departmentId
     * @return bool
     */
    public function canRegisterForDepartment(User $user, int $departmentId): bool
    {
        $department = \App\Models\Department::findOrFail($departmentId);
        
        // Check gender matching
        if ($user->gender !== $department->student_gender) {
            return false;
        }
        
        // Check country restrictions if applicable
        if ($department->restrict_countries && $user->country_id) {
            return $department->allowedCountries()
                ->where('country_id', $user->country_id)
                ->exists();
        }
        
        return true;
    }
} 