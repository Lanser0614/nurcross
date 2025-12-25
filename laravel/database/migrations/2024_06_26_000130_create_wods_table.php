<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type'); // for_time, amrap, emom, strength
            $table->string('difficulty')->default('intermediate');
            $table->integer('time_cap_seconds')->nullable();
            $table->boolean('is_benchmark')->default(false);
            $table->boolean('is_published')->default(true);
            $table->text('description'); // formatted workout text
            $table->text('strategy_notes')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wods');
    }
};
