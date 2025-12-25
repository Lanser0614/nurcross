<?php

namespace Database\Factories;

use App\Models\Gym;
use App\Models\User;
use App\Models\Wod;
use App\Models\WodResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WodResult>
 */
class WodResultFactory extends Factory
{
    protected $model = WodResult::class;

    public function definition(): array
    {
        $time = $this->faker->numberBetween(360, 1500);
        $totalReps = $this->faker->numberBetween(100, 300);
        $isRx = $this->faker->boolean(70);

        return [
            'wod_id' => Wod::factory(),
            'user_id' => User::factory(),
            'gym_id' => Gym::factory(),
            'time_in_seconds' => $time,
            'total_reps' => null,
            'weight_in_kg' => $this->faker->randomElement([null, 60, 70, 80]),
            'is_rx' => $isRx,
            'result_scale' => $isRx ? 'rx' : 'scaled',
            'score_display' => gmdate('i:s', $time),
            'notes' => $this->faker->optional()->sentence(),
            'performed_at' => $this->faker->dateTimeBetween('-4 weeks', 'now'),
        ];
    }
}
