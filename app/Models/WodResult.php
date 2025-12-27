<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WodResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'wod_id',
        'user_id',
        'gym_id',
        'time_in_seconds',
        'total_reps',
        'weight_in_kg',
        'is_rx',
        'result_scale',
        'score_display',
        'notes',
        'performed_at',
    ];

    protected $casts = [
        'time_in_seconds' => 'integer',
        'total_reps' => 'integer',
        'weight_in_kg' => 'decimal:2',
        'is_rx' => 'boolean',
        'performed_at' => 'datetime',
    ];

    public function wod(): BelongsTo
    {
        return $this->belongsTo(Wod::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }
}
