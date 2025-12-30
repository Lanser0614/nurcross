<?php

namespace Database\Seeders;

use App\Enums\EventCategory;
use App\Models\Coach;
use App\Models\Event;
use App\Models\Gym;
use App\Models\Movement;
use App\Models\User;
use App\Models\Wod;
use App\Models\WodResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CrossfitDemoSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->seedUsers();
        $gyms = $this->seedGymsWithCoaches();
        $movements = $this->seedMovements();
        $wods = $this->seedWods($gyms, $movements);
        $this->seedEvents($gyms);
        $this->seedResults($wods, $users);
    }

    protected function seedUsers(): Collection
    {
        $profiles = [
            ['name' => 'Rustam Ismailov', 'email' => 'rustam@crossfit.uz'],
            ['name' => 'Dilshod Akhmedov', 'email' => 'dilshod@crossfit.uz'],
            ['name' => 'Malika Karimova', 'email' => 'malika@crossfit.uz'],
        ];

        return collect($profiles)->map(fn (array $profile) => User::factory()->create($profile));
    }

    protected function seedGymsWithCoaches(): Collection
    {
        $gymPresets = [
            [
                'name' => 'Savage Sport Club',
                'city' => 'Tashkent',
                'type' => 'fitness club / functional training',
                'description' => 'Популярный спортивный клуб с функциональными зонами и элементами CrossFit-стиля тренировок по адресу ул. Ифтихор, 120. :contentReference[oaicite:0]{index=0}',
                'latitude' => 41.347256,  // ул. Ифтихор, 120, Юнусабадский район, Ташкент :contentReference[oaicite:1]{index=1}
                'longitude' => 69.280689,
                'email' => null,
                'phone' => null,
                'instagram' => 'https://www.instagram.com/savage.sports.club/',
            ],
            [
                'name' => 'Tribe Functional Training',
                'city' => 'Tashkent',
                'type' => 'crossfit / functional gym',
                'description' => 'Спортивный тренажёрный зал с кроссфит- и функциональными программами на ул. Паркент, 131. :contentReference[oaicite:2]{index=2}',
                'latitude' => 41.315736,  // ул. Паркент, 131, Мирзо-Улугбекский район, Ташкент :contentReference[oaicite:3]{index=3}
                'longitude' => 69.326817,
                'email' => null,
                'phone' => null,
                'instagram' => 'https://www.instagram.com/tashkent_tribe/'
            ],
            [
                'name' => 'Medion Sport',
                'city' => 'Tashkent',
                'type' => 'gym / fitness center',
                'description' => 'Фитнес-центр с зонами функционального тренинга и силовыми программами (часто посещается любителями CrossFit-стилей).',
                'latitude' => 41.326477,  // ориентировочно по карте Ташкента (Абдуллы Кадыри 39/1) — пример, уточню при надобности
                'longitude' => 69.249212,
                'email' => null,
                'phone' => null,
                'instagram' => 'https://www.instagram.com/medionsport/'
            ],
            [
                'name' => 'BeFit BOX',
                'city' => 'Tashkent',
                'type' => 'functional & CrossFit training space',
                'description' => 'Зона функционального тренинга с CrossFit-оборудованием и программами внутри сети BeFit. ',
                'latitude' => 41.324358,  // ориентировочно из карт 2GIS по адресу BeFit BOX Tashkent
                'longitude' => 69.295950,
                'email' => null,
                'phone' => null,
                'instagram' => 'https://www.instagram.com/befitbox.uz/'
            ],
            [
                'name' => 'BUKA GYM Training Center',
                'city' => 'Tashkent',
                'type' => 'crossfit / functional gym & fight zone',
                'description' => 'Тренировочный центр с зонами для CrossFit, функциональных тренировок и боевых искусств (бойцовская зона, силовая) на ул. 9-й проезд Абдуллы Каххара, 1А. Афиллированный CrossFit-клуб с программами Functional Training, CrossFit, Mobility и др. :contentReference[oaicite:0]{index=0}',
                'latitude' => 41.311900, // приближённо — ориентировочно по адресу и карте 2GIS
                'longitude' => 69.266500,
                'email' => null,
                'phone' => null,
                'instagram' => 'https://www.instagram.com/bukagym/'
            ],
        ];

        return collect($gymPresets)->map(function (array $preset) {
            $gym = Gym::factory()->create([
                'name' => $preset['name'],
                'slug' => Str::slug($preset['name']),
                'city' => $preset['city'],
                'type' => $preset['type'],
                'description' => $preset['description'],
                'latitude' => $preset['latitude'],
                'longitude' => $preset['longitude'],
                'email' => $preset['email'],
                'phone' => $preset['phone'],
                'instagram' => $preset['instagram'],
            ]);

            Coach::factory()->count(3)->create(['gym_id' => $gym->id]);

            return $gym;
        });
    }

    protected function seedMovements(): Collection
    {
        return Movement::factory()->count(18)->create();
    }

    protected function seedWods(Collection $gyms, Collection $movements): Collection
    {
        $wodPresets = [
            [
                'title' => 'Registan Fire',
                'type' => 'for_time',
                'difficulty' => 'advanced',
                'time_cap_seconds' => 900,
                'description' => "21-15-9 Thrusters (43/30kg)\nChest-to-Bar Pull-ups\n400m Run after each round.",
                'is_benchmark' => true,
                'description_translations' => [
                    'ru' => "21-15-9 трастеры (43/30 кг)\nПодтягивания к груди\n400 м бег после каждого раунда.",
                    'uz' => "21-15-9 thruster (43/30 kg)\nKo'krakka tortilish\nHar bir raunddan so'ng 400 m yugurish.",
                ],
                'strategy_notes_translations' => [
                    'ru' => 'Старайся делать подходы без остановок и контролируй дыхание на переходах.',
                    'uz' => "Setlarni bo'lmasdan bajar va o'tishlarda nafasni boshqar.",
                ],
            ],
            [
                'title' => 'Silk Road Grinder',
                'type' => 'amrap',
                'difficulty' => 'intermediate',
                'time_cap_seconds' => 1200,
                'description' => "AMRAP 20:\n200m Sandbag Carry\n20 Box Jump Overs\n15 Toes-to-Bar\n12 Deadlifts (100/70kg)",
                'description_translations' => [
                    'ru' => "AMRAP 20:\n200 м переноска мешка с песком\n20 прыжков Box Jump Over\n15 Toes-to-Bar\n12 становая тяга (100/70 кг)",
                    'uz' => "AMRAP 20:\n200 m qum xaltasini ko'tarib yurish\n20 box jump over\n15 toes-to-bar\n12 deadlift (100/70 kg)",
                ],
                'strategy_notes_translations' => [
                    'ru' => 'Поддерживай ровное дыхание и держи темп на переноске мешка.',
                    'uz' => "Nafasni barqaror ushla va qum xaltasida tempni saqla.",
                ],
            ],
            [
                'title' => 'Steppe Engine EMOM',
                'type' => 'emom',
                'difficulty' => 'intermediate',
                'description' => "EMOM 18:\n1) 12/10 Cal Assault Bike\n2) 10 Burpee Box Jump Overs\n3) 8 Hang Power Cleans (60/40kg)",
                'description_translations' => [
                    'ru' => "EMOM 18:\n1) 12/10 кал Assault Bike\n2) 10 Burpee Box Jump Over\n3) 8 Hang Power Clean (60/40 кг)",
                    'uz' => "EMOM 18:\n1) 12/10 kal Assault Bike\n2) 10 burpee box jump over\n3) 8 hang power clean (60/40 kg)",
                ],
                'strategy_notes_translations' => [
                    'ru' => 'Работай точно по минутам, оставляй 10-15 секунд отдыха.',
                    'uz' => "Har daqiqada barqaror ishlang, 10-15 soniya dam qoldiring.",
                ],
            ],
            [
                'title' => 'Aral Strength Ladder',
                'type' => 'strength',
                'difficulty' => 'advanced',
                'description' => "10-8-6-4-2 Front Squat\nBuild to a heavy double.",
                'description_translations' => [
                    'ru' => "10-8-6-4-2 фронтальные приседы\nДойди до тяжёлой двойки.",
                    'uz' => "10-8-6-4-2 old squat\nOg'ir juftlikka chiq.",
                ],
                'strategy_notes_translations' => [
                    'ru' => 'Следи за техникой и добавляй вес только при уверенности.',
                    'uz' => "Texnikani saqla va faqat ishonch bilan vazn qo'sh.",
                ],
            ],
            [
                'title' => 'Tashkent Sprint',
                'type' => 'for_time',
                'difficulty' => 'beginner',
                'time_cap_seconds' => 600,
                'description' => "4 rounds:\n15 Wall Balls (9/6kg)\n12 Kettlebell Swings (24/16kg)\n200m Run",
                'description_translations' => [
                    'ru' => "4 раунда:\n15 Wall Ball (9/6 кг)\n12 махов гирей (24/16 кг)\n200 м бег",
                    'uz' => "4 raund:\n15 wall ball (9/6 kg)\n12 kettlebell swing (24/16 kg)\n200 m yugurish",
                ],
                'strategy_notes_translations' => [
                    'ru' => 'Держи ровный темп и не сбрасывай ритм на беге.',
                    'uz' => "Tekis tempni saqla va yugurishda ritmni tushirma.",
                ],
            ],
        ];

        $pivotNotesRu = [
            'Делай паузы только перед тяжёлым подходом.',
            'Считай повторения вслух, чтобы не сбиться.',
            'Работай сериями и не перегревайся.',
        ];

        $pivotNotesUz = [
            'Faqat og\'ir set oldidan pauza qil.',
            'Takrorlarni baland ovozda sanab bor.',
            'Setlarni bo\'lib bajar va qizib ketma.',
        ];

        return collect($wodPresets)->map(function (array $preset) use ($gyms, $movements, $pivotNotesRu, $pivotNotesUz) {
            $gym = $gyms->random();
            $wod = Wod::factory()->create([
                'gym_id' => $gym->id,
                'title' => $preset['title'],
                'slug' => Str::slug($preset['title']),
                'type' => $preset['type'],
                'difficulty' => $preset['difficulty'],
                'time_cap_seconds' => $preset['time_cap_seconds'] ?? null,
                'is_benchmark' => $preset['is_benchmark'] ?? false,
                'description' => $preset['description'],
                'strategy_notes' => 'Maintain unbroken sets, breathe through transitions.',
                'description_translations' => $preset['description_translations'] ?? null,
                'strategy_notes_translations' => $preset['strategy_notes_translations'] ?? null,
            ]);

            $selectedMovements = $movements->random(rand(3, 4));
            $repSchemes = ['21-15-9', '5x5', 'EMOM 10', 'AMRAP 12', '3 Rounds'];

            foreach ($selectedMovements as $index => $movement) {
                $repScheme = $repSchemes[array_rand($repSchemes)];
                $load = fake()->randomElement(['50/35kg', '60/40kg', 'Bodyweight', '24/16kg']);
                $note = fake()->sentence();

                $wod->movements()->attach($movement->id, [
                    'position' => $index + 1,
                    'rep_scheme' => $repScheme,
                    'load' => $load,
                    'notes' => $note,
                    'rep_scheme_translations' => json_encode([
                        'ru' => $repScheme,
                        'uz' => $repScheme,
                    ], JSON_UNESCAPED_UNICODE),
                    'load_translations' => json_encode([
                        'ru' => $load,
                        'uz' => $load,
                    ], JSON_UNESCAPED_UNICODE),
                    'notes_translations' => json_encode([
                        'ru' => $pivotNotesRu[array_rand($pivotNotesRu)],
                        'uz' => $pivotNotesUz[array_rand($pivotNotesUz)],
                    ], JSON_UNESCAPED_UNICODE),
                ]);
            }

            return $wod;
        });
    }

    protected function seedEvents(Collection $gyms): void
    {
        $events = [
            [
                'title' => 'Tashkent Throwdown',
                'category' => EventCategory::COMPETITION,
                'city' => 'Tashkent',
                'address' => 'Savage Sport Club',
                'start_at' => now()->addWeeks(2)->setTime(10, 0),
                'end_at' => now()->addWeeks(2)->setTime(18, 0),
                'registration_url' => 'https://crossfit.uz/events/throwdown',
                'gym_name' => 'Savage Sport Club',
                'description_en' => 'One-day throwdown for RX and scaled athletes with individual and team heats.',
                'description_ru' => 'Однодневный throwdown для RX и scaled атлетов с индивидуальными и командными забегами.',
                'description_uz' => "Bir kunlik throwdown: RX va scaled atletlar uchun individual hamda jamoaviy startlar.",
            ],
            [
                'title' => 'Tribe Skills Camp',
                'category' => EventCategory::SEMINAR,
                'city' => 'Tashkent',
                'address' => 'Tribe Functional Training',
                'start_at' => now()->addWeeks(4)->setTime(11, 30),
                'end_at' => now()->addWeeks(4)->setTime(16, 0),
                'registration_url' => 'https://tribe.uz/camp',
                'gym_name' => 'Tribe Functional Training',
                'description_en' => 'Technical workshop dedicated to gymnastics progressions and barbell cycling.',
                'description_ru' => 'Технический кемп по гимнастическим прогрессиям и работе со штангой.',
                'description_uz' => "Gimnastika progressiyalari va shtanga bilan ishlash bo'yicha texnik lager.",
            ],
            [
                'title' => 'Savage Community Murph',
                'category' => EventCategory::COMMUNITY,
                'city' => 'Tashkent',
                'address' => 'Savage Sport Club',
                'start_at' => now()->addWeeks(6)->setTime(9, 0),
                'end_at' => now()->addWeeks(6)->setTime(12, 0),
                'registration_url' => null,
                'gym_name' => 'Savage Sport Club',
                'description_en' => 'Community meetup to complete Murph together and support local charity.',
                'description_ru' => 'Комьюнити-встреча: выполняем Murph вместе и поддерживаем местный благотворительный фонд.',
                'description_uz' => "Hamjamiyat uchrashuvi: birgalikda Murph bajarib, xayriya jamg'armasini qo'llab-quvvatlaymiz.",
            ],
            [
                'title' => 'Silk Road Qualifier',
                'category' => EventCategory::QUALIFIER,
                'city' => 'Online',
                'address' => 'Online',
                'start_at' => now()->addWeeks(3)->setTime(0, 0),
                'end_at' => now()->addWeeks(5)->setTime(23, 0),
                'registration_url' => 'https://silkroadcomp.com/qualifier',
                'gym_name' => null,
                'description_en' => 'Online qualifier for the Silk Road CrossFit festival finals in Samarkand.',
                'description_ru' => 'Онлайн-квалификация на финал фестиваля Silk Road CrossFit в Самарканде.',
                'description_uz' => "Silk Road CrossFit festivali finali (Samarqand) uchun onlayn saralash.",
            ],
        ];

        $gymsByName = $gyms->keyBy('name');

        foreach ($events as $preset) {
            $gym = $preset['gym_name']
                ? $gymsByName->get($preset['gym_name'])
                : $gyms->random();

            Event::create([
                'gym_id' => $gym?->id,
                'title' => $preset['title'],
                'category' => $preset['category'],
                'city' => $preset['city'],
                'address' => $preset['address'],
                'start_at' => $preset['start_at'],
                'end_at' => $preset['end_at'],
                'registration_url' => $preset['registration_url'],
                'description' => [
                    'en' => $preset['description_en'],
                    'ru' => $preset['description_ru'],
                    'uz' => $preset['description_uz'],
                ],
            ]);
        }

        Event::factory()
            ->count(4)
            ->create();
    }

    protected function seedResults(Collection $wods, Collection $users): void
    {
        foreach ($wods as $wod) {
            foreach ($users as $user) {
                $isRx = fake()->boolean(75);
                $performedAt = now()->subDays(fake()->numberBetween(1, 21));

                if ($wod->type === 'amrap') {
                    $totalReps = fake()->numberBetween(150, 260);
                    $scoreDisplay = sprintf('%d + %d', intdiv($totalReps, 30), $totalReps % 30);

                    WodResult::factory()->create([
                        'wod_id' => $wod->id,
                        'user_id' => $user->id,
                        'gym_id' => $wod->gym_id,
                        'time_in_seconds' => null,
                        'total_reps' => $totalReps,
                        'weight_in_kg' => fake()->randomElement([null, 60, 70]),
                        'is_rx' => $isRx,
                        'result_scale' => $isRx ? 'rx' : 'scaled',
                        'score_display' => $scoreDisplay,
                        'notes' => fake()->optional()->sentence(),
                        'performed_at' => $performedAt,
                    ]);

                    continue;
                }

                $timeInSeconds = fake()->numberBetween(420, ($wod->time_cap_seconds ?? 1200));
                $scoreDisplay = gmdate('i:s', $timeInSeconds);

                WodResult::factory()->create([
                    'wod_id' => $wod->id,
                    'user_id' => $user->id,
                    'gym_id' => $wod->gym_id,
                    'time_in_seconds' => $wod->type === 'strength' ? null : $timeInSeconds,
                    'total_reps' => $wod->type === 'strength'
                        ? fake()->numberBetween(1, 5)
                        : null,
                    'weight_in_kg' => $wod->type === 'strength'
                        ? fake()->numberBetween(60, 130)
                        : fake()->randomElement([null, 70, 80]),
                    'is_rx' => $isRx,
                    'result_scale' => $isRx ? 'rx' : 'scaled',
                    'score_display' => $scoreDisplay,
                    'notes' => fake()->optional()->sentence(),
                    'performed_at' => $performedAt,
                ]);
            }
        }
    }
}
