<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'video_path',
        'video_disk',
        'video_original_name',
        'video_size',
        'notes',
    ];

    protected $attributes = [
        'video_disk' => 'public',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getVideoUrlAttribute(): ?string
    {
        if (! $this->video_path) {
            return null;
        }

        $disk = $this->video_disk ?: 'public';

        return Storage::disk($disk)->url($this->video_path);
    }

    protected static function booted(): void
    {
        static::deleting(function (EventRegistration $registration): void {
            if ($registration->video_path) {
                $disk = $registration->video_disk ?: 'public';
                Storage::disk($disk)->delete($registration->video_path);
            }
        });
    }
}
