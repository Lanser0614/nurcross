<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movement_wod', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wod_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(1); // ordering within the workout
            $table->string('rep_scheme')->nullable(); // e.g. 21-15-9
            $table->string('load')->nullable(); // e.g. 50/35kg
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['movement_id', 'wod_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movement_wod');
    }
};
