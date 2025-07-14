<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->enum('assignment_type', ['user', 'role']); // Assign to specific user or role
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('assigned_to_role', ['super_admin', 'department_admin', 'teacher', 'supervisor'])->nullable();
            $table->date('assigned_date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue', 'cancelled'])->default('pending');
            $table->timestamps();
            
            // Either assign to user or role, not both
            $table->index(['task_id', 'assigned_to_user_id']);
            $table->index(['task_id', 'assigned_to_role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
}; 