<?php

namespace Database\Factories;

use App\Enums\EventCategory;
use App\Models\Event;
use App\Models\Gym;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);
        $start = $this->faker->dateTimeBetween('+1 week', '+3 months');

        return [
            'gym_id' => Gym::inRandomOrder()->value('id'),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(4),
            'category' => $this->faker->randomElement(array_map(fn (EventCategory $case) => $case->value, EventCategory::cases())),
            'start_at' => $start,
            'end_at' => (clone $start)->modify('+2 hours'),
            'city' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'registration_url' => $this->faker->optional()->url(),
            'is_featured' => $this->faker->boolean(20),
            'description' => [
                'en' => $this->faker->paragraph(),
                'ru' => $this->faker->paragraph(),
                'uz' => $this->faker->paragraph(),
            ],
        ];
    }
}
