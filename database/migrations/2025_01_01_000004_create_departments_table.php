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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('student_gender', ['male', 'female', 'mixed']);
            $table->boolean('work_friday')->default(false);
            $table->boolean('work_saturday')->default(false);
            $table->boolean('work_sunday')->default(false);
            $table->boolean('work_monday')->default(false);
            $table->boolean('work_tuesday')->default(false);
            $table->boolean('work_wednesday')->default(false);
            $table->boolean('work_thursday')->default(false);
            $table->integer('monthly_fees')->nullable();
            $table->integer('quarterly_fees')->nullable();
            $table->integer('biannual_fees')->nullable();
            $table->integer('annual_fees')->nullable();
            $table->boolean('restrict_countries')->default(false)
                ->comment('If true, only students from allowed countries can register');
            $table->boolean('registration_open')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
}; 