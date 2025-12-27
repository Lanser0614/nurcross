<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ru')->nullable();
            $table->string('slug')->unique();
            $table->string('category'); // weightlifting, gymnastics, monostructural
            $table->string('difficulty')->default('intermediate');
            $table->string('equipment')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->text('description')->nullable();
            $table->text('technique_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
