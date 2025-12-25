<?php

namespace Database\Factories;

use App\Models\Coach;
use App\Models\Gym;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coach>
 */
class CoachFactory extends Factory
{
    protected $model = Coach::class;

    public function definition(): array
    {
        $name = $this->faker->name();

        return [
            'gym_id' => Gym::factory(),
            'full_name' => $name,
            'slug' => Str::slug($name . '-' . $this->faker->unique()->numberBetween(1, 999)),
            'role' => $this->faker->randomElement(['Head Coach', 'Weightlifting Specialist', 'Endurance Coach']),
            'specialties' => implode(', ', $this->faker->randomElements(
                ['Olympic Lifting', 'Gymnastics', 'Engine Building', 'Mobility', 'Competition Prep'],
                2
            )),
            'certifications' => $this->faker->randomElement([
                'CrossFit Level 1',
                'CrossFit Level 2',
                'USAW Level 1',
            ]),
            'phone' => $this->faker->optional()->phoneNumber(),
            'email' => $this->faker->optional()->safeEmail(),
            'instagram' => $this->faker->optional()->userName(),
            'photo_url' => $this->faker->optional()->imageUrl(400, 400, 'people', true),
            'bio' => $this->faker->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
