<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ru',
        'slug',
        'category',
        'difficulty',
        'equipment',
        'thumbnail_url',
        'youtube_url',
        'description',
        'technique_notes',
        'description_translations',
        'technique_notes_translations',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'description_translations' => 'array',
        'technique_notes_translations' => 'array',
    ];

    public function wods(): BelongsToMany
    {
        return $this->belongsToMany(Wod::class)
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getDescriptionLocalizedAttribute(): ?string
    {
        return $this->translateValue($this->description_translations, $this->description);
    }

    public function getTechniqueNotesLocalizedAttribute(): ?string
    {
        return $this->translateValue($this->technique_notes_translations, $this->technique_notes);
    }

    public function getPivotRepSchemeLocalizedAttribute(): ?string
    {
        return $this->pivotLocalized('rep_scheme');
    }

    public function getPivotLoadLocalizedAttribute(): ?string
    {
        return $this->pivotLocalized('load');
    }

    public function getPivotNotesLocalizedAttribute(): ?string
    {
        return $this->pivotLocalized('notes');
    }

    protected function translateValue(mixed $translations, ?string $fallback): ?string
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
        $default = config('app.fallback_locale', 'en');

        return $translations[$locale] ?? $translations[$default] ?? $fallback;
    }

    protected function pivotLocalized(string $field): ?string
    {
        if (! $this->pivot) {
            return null;
        }

        $translations = $this->pivot->{$field.'_translations'} ?? null;
        $fallback = $this->pivot->{$field} ?? null;

        return $this->translateValue($translations, $fallback);
    }
}
