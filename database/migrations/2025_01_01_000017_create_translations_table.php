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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained();
            $table->string('translation_key');
            $table->text('translation_value');
            $table->timestamps();
            
            $table->unique(['language_id', 'translation_key'], 'unique_translation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};