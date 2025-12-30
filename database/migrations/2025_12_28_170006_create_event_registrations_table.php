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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Event::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('video_path');
            $table->string('video_disk')
                ->default('public');
            $table->string('video_original_name')
                ->nullable();
            $table->unsignedBigInteger('video_size')
                ->nullable();
            $table->text('notes')
                ->nullable();
            $table->timestamps();

            $table->index(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
