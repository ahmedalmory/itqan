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
        Schema::create('task_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_assignment_id')->constrained('task_assignments')->onDelete('cascade');
            $table->foreignId('completed_by')->constrained('users');
            $table->datetime('completed_at');
            $table->text('notes')->nullable(); // Comments/notes when marking as done
            $table->json('attachments')->nullable(); // For future file attachments
            $table->boolean('is_completed')->default(true);
            $table->timestamps();
            
            $table->index(['task_assignment_id', 'completed_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_completions');
    }
}; 