<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wods', function (Blueprint $table) {
            $table->json('description_translations')->nullable();
            $table->json('strategy_notes_translations')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('wods', function (Blueprint $table) {
            $table->dropColumn(['description_translations', 'strategy_notes_translations']);
        });
    }
};
