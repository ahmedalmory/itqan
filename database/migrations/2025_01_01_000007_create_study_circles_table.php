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
        Schema::create('study_circles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('teacher_id')->nullable()->constrained('users');
            $table->foreignId('supervisor_id')->nullable()->constrained('users');
            $table->integer('max_students')->nullable();
            $table->string('whatsapp_group')->nullable();
            $table->string('telegram_group')->nullable();
            $table->integer('age_from');
            $table->integer('age_to');
            $table->enum('circle_time', [
                'after_fajr', 'after_dhuhr', 'after_asr', 'after_maghrib', 'after_isha'
            ])->nullable();
            $table->string('location')->nullable();
            $table->time('meeting_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_circles');
    }
};