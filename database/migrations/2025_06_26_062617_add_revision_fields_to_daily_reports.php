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
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->foreignId('revision_from_surah')->nullable()->constrained('surahs')->after('memorization_to_verse');
            $table->integer('revision_from_verse')->nullable()->after('revision_from_surah');
            $table->foreignId('revision_to_surah')->nullable()->constrained('surahs')->after('revision_from_verse');
            $table->integer('revision_to_verse')->nullable()->after('revision_to_surah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropForeign(['revision_from_surah']);
            $table->dropForeign(['revision_to_surah']);
            $table->dropColumn(['revision_from_surah', 'revision_from_verse', 'revision_to_surah', 'revision_to_verse']);
        });
    }
};
