<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Wod;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\Support\Attributes\Icon;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

#[Icon('fire')]
#[Group('Programming')]
/**
 * @extends ModelResource<Wod>
 */
class WodResource extends ModelResource
{
    protected string $model = Wod::class;

    protected string $column = 'title';

    protected array $with = ['gym'];

    public function title(): string
    {
        return 'WODs';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Title', 'title')->sortable(),
            Text::make('Type', 'type')
                ->badge()
                ->changePreview(fn (?string $value): string => strtoupper(str_replace('_', ' ', (string) $value))),
            Text::make('Difficulty', 'difficulty')->badge(),
            Text::make('Gym', 'gym.name'),
            Switcher::make('Benchmark', 'is_benchmark'),
            Switcher::make('Published', 'is_published'),
            Switcher::make('WOD of the day', 'is_wod_of_day'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make('Gym', 'gym', resource: GymResource::class)
                    ->nullable()
                    ->searchable(),
                Text::make('Title', 'title')->required(),
                Text::make('Slug', 'slug')->required(),
                Select::make('Type', 'type')
                    ->required()
                    ->options([
                        'for_time' => 'For Time',
                        'amrap' => 'AMRAP',
                        'emom' => 'EMOM',
                        'strength' => 'Strength',
                    ]),
                Select::make('Difficulty', 'difficulty')
                    ->required()
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                Number::make('Time cap (sec)', 'time_cap_seconds'),
                Switcher::make('Benchmark', 'is_benchmark'),
                Switcher::make('Published', 'is_published')
                    ->default(true),
                Switcher::make('WOD of the day', 'is_wod_of_day'),
                Date::make('Published at', 'published_at')->withTime(),
                Textarea::make('Description (EN)', 'description')->required(),
                Textarea::make('Description (RU)', 'description_translations->ru'),
                Textarea::make('Description (UZ)', 'description_translations->uz'),
                Textarea::make('Strategy (EN)', 'strategy_notes'),
                Textarea::make('Strategy (RU)', 'strategy_notes_translations->ru'),
                Textarea::make('Strategy (UZ)', 'strategy_notes_translations->uz'),
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function rules($item): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:180', 'unique:wods,slug,' . $item->getKey()],
            'type' => ['required', 'in:for_time,amrap,emom,strength'],
            'difficulty' => ['required', 'in:beginner,intermediate,advanced'],
            'description' => ['required', 'string'],
            'is_wod_of_day' => ['boolean'],
        ];
    }

    protected function search(): array
    {
        return ['title', 'type', 'difficulty'];
    }
}
