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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->date('report_date');
            $table->decimal('memorization_parts', 4, 2);
            $table->decimal('revision_parts', 4, 2);
            $table->decimal('grade', 5, 2);
            $table->foreignId('memorization_from_surah')->constrained('surahs');
            $table->integer('memorization_from_verse');
            $table->foreignId('memorization_to_surah')->constrained('surahs');
            $table->integer('memorization_to_verse');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
}; 