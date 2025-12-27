<?php

namespace Database\Factories;

use App\Models\Gym;
use App\Models\Wod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Wod>
 */
class WodFactory extends Factory
{
    protected $model = Wod::class;

    protected array $types = ['for_time', 'amrap', 'emom', 'strength'];

    protected array $difficulties = ['beginner', 'intermediate', 'advanced'];

    public function definition(): array
    {
        $title = $this->faker->unique()->randomElement([
            'Tashkent Crusher',
            'Silk Road Sprint',
            'Uzbek Engine',
            'Registan Grind',
            'Samarkand Storm',
            'Savage Steppe',
            'Aral Assault',
        ]);

        $type = $this->faker->randomElement($this->types);
        $timeCap = $type === 'strength' ? null : $this->faker->numberBetween(480, 1500);

        return [
            'gym_id' => Gym::factory(),
            'title' => $title,
            'slug' => Str::slug($title . '-' . $this->faker->unique()->numberBetween(1, 999)),
            'type' => $type,
            'difficulty' => $this->faker->randomElement($this->difficulties),
            'time_cap_seconds' => $timeCap,
            'is_benchmark' => $this->faker->boolean(25),
            'is_published' => true,
            'description' => implode("\n", $this->faker->sentences(3)),
            'strategy_notes' => $this->faker->optional()->sentence(),
            'published_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
