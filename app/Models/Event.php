<?php

namespace App\Models;

use App\Enums\EventCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'title',
        'slug',
        'category',
        'start_at',
        'end_at',
        'city',
        'address',
        'registration_url',
        'is_featured',
        'description',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_featured' => 'boolean',
        'description' => 'array',
        'description_translations' => 'array',
        'category' => EventCategory::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (Event $event): void {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title) . '-' . Str::random(5);
            }
        });
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function getDescriptionLocalizedAttribute(): ?string
    {
        $locale = app()->getLocale();
        $description = $this->description ?? [];

        return $description[$locale] ?? $description['en'] ?? null;
    }
}
