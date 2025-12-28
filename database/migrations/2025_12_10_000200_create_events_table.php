<?php

use App\Enums\EventCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('gym_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default(EventCategory::COMPETITION->value);
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('registration_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->json('description')->nullable();
            $table->timestamps();

            $table->index(['category', 'start_at']);
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
