<?php

namespace App\Models;

use App\Enums\EventCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
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
        'content_video_path',
        'content_video_disk',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_featured' => 'boolean',
        'description' => 'array',
        'description_translations' => 'array',
        'category' => EventCategory::class,
    ];

    protected $attributes = [
        'content_video_disk' => 'public',
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

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function getContentVideoUrlAttribute(): ?string
    {
        if (! $this->content_video_path) {
            return null;
        }

        $disk = $this->content_video_disk ?: 'public';

        return Storage::disk($disk)->url($this->content_video_path);
    }
}
