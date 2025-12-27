<?php

namespace Database\Factories;

use App\Models\Gym;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Gym>
 */
class GymFactory extends Factory
{
    protected $model = Gym::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company() . ' CrossFit';

        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . $this->faker->unique()->word()),
            'city' => $this->faker->randomElement(['Tashkent', 'Samarkand', 'Bukhara', 'Namangan']),
            'address' => $this->faker->streetAddress(),
            'type' => $this->faker->randomElement(['box', 'functional training']),
            'latitude' => $this->faker->latitude(41.0, 43.0),
            'longitude' => $this->faker->longitude(64.0, 71.0),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
            'website' => $this->faker->optional()->url(),
            'instagram' => $this->faker->optional()->userName(),
            'telegram' => $this->faker->optional()->userName(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
