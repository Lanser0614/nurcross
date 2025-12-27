<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movement_wod', function (Blueprint $table) {
            $table->json('rep_scheme_translations')->nullable();
            $table->json('load_translations')->nullable();
            $table->json('notes_translations')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('movement_wod', function (Blueprint $table) {
            $table->dropColumn(['rep_scheme_translations', 'load_translations', 'notes_translations']);
        });
    }
};
