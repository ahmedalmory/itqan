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
        Schema::dropIfExists('users');
        
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('national_id')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone', 20);
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->enum('role', ['super_admin', 'department_admin', 'teacher', 'supervisor', 'student']);
            $table->enum('preferred_time', [
                'after_fajr', 'after_dhuhr', 'after_asr', 'after_maghrib', 'after_isha'
            ])->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        
        // Restore original Laravel users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
}; 