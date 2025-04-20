<?php

namespace Database\Seeders;

use App\Models\DepartmentAdmin;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all department admin users
        $admins = User::where('role', 'department_admin')->get();
        
        // Get all departments
        $departments = Department::all();
        
        // Assign each admin to 1-2 departments
        foreach ($admins as $admin) {
            $numDepartments = rand(1, min(2, $departments->count()));
            $shuffledDepts = $departments->shuffle()->take($numDepartments);
            
            foreach ($shuffledDepts as $department) {
                DepartmentAdmin::create([
                    'user_id' => $admin->id,
                    'department_id' => $department->id,
                ]);
            }
        }
    }
} 