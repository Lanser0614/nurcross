<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wod_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wod_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gym_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('time_in_seconds')->nullable();
            $table->unsignedInteger('total_reps')->nullable();
            $table->decimal('weight_in_kg', 6, 2)->nullable();
            $table->boolean('is_rx')->default(true);
            $table->string('result_scale')->default('rx'); // rx, scaled, modified
            $table->string('score_display')->nullable(); // e.g. 12:35 or 5+12
            $table->text('notes')->nullable();
            $table->timestamp('performed_at')->nullable();
            $table->timestamps();

            $table->index(['wod_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wod_results');
    }
};
