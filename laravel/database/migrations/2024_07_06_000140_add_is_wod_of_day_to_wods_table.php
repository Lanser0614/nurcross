<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wods', function (Blueprint $table) {
            $table->boolean('is_wod_of_day')->default(false)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('wods', function (Blueprint $table) {
            $table->dropColumn('is_wod_of_day');
        });
    }
};
