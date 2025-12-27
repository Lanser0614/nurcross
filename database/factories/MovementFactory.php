<?php

namespace Database\Factories;

use App\Models\Movement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Movement>
 */
class MovementFactory extends Factory
{
    private const PLAYLIST_ID = 'PLdWvFCOAvyr3EWQhtfcEMd3DVM5sJdPL4';

    protected $model = Movement::class;

    protected array $categories = ['weightlifting', 'gymnastics', 'monostructural'];

    protected array $difficulties = ['beginner', 'intermediate', 'advanced'];

    protected array $ruDescriptions = [
        'Сохраняй корпус стабильным и контролируй дыхание.',
        'Работай взрывно, но не теряй технику в нижней фазе.',
        'Держи пятки прижатыми к полу и толкай колени наружу.',
    ];

    protected array $uzDescriptions = [
        'Tananingni barqaror ushla va nafasni boshqar.',
        'Portlovchi ishlang, lekin pastki fazada texnikani yo\'qotmang.',
        'Tovonlarni polga bosib, tizzalarni tashqariga itar.',
    ];

    protected array $ruTechniqueTips = [
        'Включай кор и фиксируй лопатки перед подъёмом.',
        'Локти высоко, взгляд вперёд, движение без рывков.',
        'Приземляйся мягко, сохраняя одну линию тела.',
    ];

    protected array $uzTechniqueTips = [
        'Korni faollashtir va yelka pichoqlarini barqarorla.',
        'Tirsaklarni yuqori ko\'tar va qarashni oldinga qarat.',
        'Yumshoq tush va tanani bir chiziqda ushla.',
    ];

    protected array $movementPlaylistLookup = [
        'Snatch' => 45,
        'Clean and Jerk' => 47,
        'Thruster' => 14,
        'Pull-up' => 7,
        'Toes-to-Bar' => 50,
        'Muscle-up' => 52,
        'Handstand Push-up' => 54,
        'Double-Under' => 34,
        'Wall Ball Shot' => 30,
        'Deadlift' => 11,
        'Front Squat' => 12,
        'Burpee Box Jump-over' => 26,
        'Row' => 74,
        'Assault Bike' => 78,
        'Run 400m' => 80,
        'GHD Sit-up' => 58,
    ];

    public function definition(): array
    {
        $movementName = $this->faker->randomElement([
            'Snatch',
            'Clean and Jerk',
            'Thruster',
            'Pull-up',
            'Toes-to-Bar',
            'Muscle-up',
            'Handstand Push-up',
            'Double-Under',
            'Wall Ball Shot',
            'Deadlift',
            'Front Squat',
            'Burpee Box Jump-over',
            'Row',
            'Assault Bike',
            'Run 400m',
            'GHD Sit-up',
        ]);

        $suffix = $this->faker->randomElement(['', 'Complex', 'Flow', 'Cycle', 'Sprint']);
        $name = trim($movementName.' '.$suffix);

        $category = $this->faker->randomElement($this->categories);

        $description = $this->faker->paragraph();
        $techniqueNotes = implode(' ', $this->faker->sentences(2));

        $playlistIndex = $this->movementPlaylistLookup[$movementName] ?? $this->faker->numberBetween(1, 120);
        $playlistUrl = sprintf(
            'https://www.youtube.com/playlist?list=%s&index=%d',
            self::PLAYLIST_ID,
            $playlistIndex
        );

        return [
            'name' => $name,
            'name_ru' => $this->faker->words(2, true),
            'slug' => Str::slug($name . '-' . $this->faker->unique()->numberBetween(100, 9999)),
            'category' => $category,
            'difficulty' => $this->faker->randomElement($this->difficulties),
            'equipment' => $category === 'monostructural'
                ? $this->faker->randomElement(['Rower', 'Bike', 'Track', 'Jump Rope'])
                : $this->faker->randomElement(['Barbell', 'Dumbbells', 'Kettlebell', 'Rig']),
            'thumbnail_url' => $this->faker->optional()->imageUrl(640, 360, 'sports', true),
            'youtube_url' => $playlistUrl,
            'description' => $description,
            'technique_notes' => $techniqueNotes,
            'description_translations' => [
                'ru' => $name . '. ' . $this->faker->randomElement($this->ruDescriptions),
                'uz' => $name . '. ' . $this->faker->randomElement($this->uzDescriptions),
            ],
            'technique_notes_translations' => [
                'ru' => $this->faker->randomElement($this->ruTechniqueTips),
                'uz' => $this->faker->randomElement($this->uzTechniqueTips),
            ],
        ];
    }
}
