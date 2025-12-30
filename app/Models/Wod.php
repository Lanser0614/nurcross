<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wod extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'title',
        'slug',
        'type',
        'difficulty',
        'time_cap_seconds',
        'is_benchmark',
        'is_published',
        'is_wod_of_day',
        'description',
        'strategy_notes',
        'description_translations',
        'strategy_notes_translations',
        'published_at',
    ];

    protected $casts = [
        'is_benchmark' => 'boolean',
        'is_published' => 'boolean',
        'is_wod_of_day' => 'boolean',
        'time_cap_seconds' => 'integer',
        'published_at' => 'datetime',
        'description_translations' => 'array',
        'strategy_notes_translations' => 'array',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function movements(): BelongsToMany
    {
        return $this->belongsToMany(Movement::class)
            ->withPivot([
                'position',
                'rep_scheme',
                'rep_scheme_translations',
                'load',
                'load_translations',
                'notes',
                'notes_translations',
            ])
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function results(): HasMany
    {
        return $this->hasMany(WodResult::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getDescriptionLocalizedAttribute(): string
    {
        return $this->getTranslatedAttribute('description_translations', $this->description);
    }

    public function getStrategyNotesLocalizedAttribute(): ?string
    {
        return $this->getTranslatedAttribute('strategy_notes_translations', $this->strategy_notes);
    }

    protected static function booted(): void
    {
        static::saved(function (Wod $wod): void {
            if (! $wod->is_wod_of_day) {
                return;
            }

            static::whereKeyNot($wod->getKey())
                ->where('is_wod_of_day', true)
                ->update(['is_wod_of_day' => false]);
        });
    }

    public static function translateDifficulty(?string $difficulty): string
    {
        $map = [
            'beginner' => __('text.Beginner'),
            'intermediate' => __('text.Intermediate'),
            'advanced' => __('text.Advanced'),
        ];

        if (! $difficulty) {
            return '';
        }

        return $map[strtolower($difficulty)] ?? ucfirst($difficulty);
    }

    public function getDifficultyTranslatedAttribute(): string
    {
        return static::translateDifficulty($this->difficulty);
    }

    public static function translateType(?string $type): string
    {
        $map = [
            'for_time' => __('text.For Time'),
            'amrap' => __('text.AMRAP'),
            'emom' => __('text.EMOM'),
            'strength' => __('text.Strength'),
        ];

        if (! $type) {
            return '';
        }

        $normalized = strtolower($type);

        return $map[$normalized] ?? ucfirst(str_replace('_', ' ', $type));
    }

    public function getTypeTranslatedAttribute(): string
    {
        return static::translateType($this->type);
    }

    protected function getTranslatedAttribute(mixed $translations, ?string $fallback): ?string
    {
        if (empty($translations)) {
            return $fallback;
        }

        if (is_string($translations)) {
            $decoded = json_decode($translations, true);
            $translations = is_array($decoded) ? $decoded : null;
        }

        if (! is_array($translations)) {
            return $fallback;
        }

        $locale = app()->getLocale();

        if (isset($translations[$locale])) {
            return $translations[$locale];
        }

        $default = config('app.fallback_locale', 'en');

        return $translations[$default] ?? $fallback;
    }
}
