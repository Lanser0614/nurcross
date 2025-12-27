<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gym extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'city',
        'address',
        'type',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'instagram',
        'telegram',
        'description',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function coaches(): HasMany
    {
        return $this->hasMany(Coach::class);
    }

    public function wods(): HasMany
    {
        return $this->hasMany(Wod::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

}
