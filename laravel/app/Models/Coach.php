<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'full_name',
        'slug',
        'role',
        'specialties',
        'certifications',
        'phone',
        'email',
        'instagram',
        'photo_url',
        'bio',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }
}
