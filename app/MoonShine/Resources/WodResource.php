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
#[Group('Программинг')]
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
        return 'WODы';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'title')->sortable(),
            Text::make('Тип', 'type')
                ->badge()
                ->changePreview(fn (?string $value): string => strtoupper(str_replace('_', ' ', (string) $value))),
            Text::make('Сложность', 'difficulty')->badge(),
            Text::make('Зал', 'gym.name'),
            Switcher::make('Бенчмарк', 'is_benchmark'),
            Switcher::make('Опубликовано', 'is_published'),
            Switcher::make('WOD дня', 'is_wod_of_day'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make('Зал', 'gym', resource: GymResource::class)
                    ->nullable()
                    ->searchable(),
                Text::make('Название', 'title')->required(),
                Text::make('Слаг', 'slug')->required(),
                Select::make('Тип', 'type')
                    ->required()
                    ->options([
                        'for_time' => 'На время',
                        'amrap' => 'AMRAP',
                        'emom' => 'EMOM',
                        'strength' => 'Силовая',
                    ]),
                Select::make('Сложность', 'difficulty')
                    ->required()
                    ->options([
                        'beginner' => 'Новичок',
                        'intermediate' => 'Средний',
                        'advanced' => 'Продвинутый',
                    ]),
                Number::make('Лимит времени (сек)', 'time_cap_seconds'),
                Switcher::make('Бенчмарк', 'is_benchmark'),
                Switcher::make('Опубликовано', 'is_published')
                    ->default(true),
                Switcher::make('WOD дня', 'is_wod_of_day'),
                Date::make('Дата публикации', 'published_at')->withTime(),
                Textarea::make('Описание (EN)', 'description')->required(),
                Textarea::make('Описание (RU)', 'description_translations->ru'),
                Textarea::make('Описание (UZ)', 'description_translations->uz'),
                Textarea::make('Стратегия (EN)', 'strategy_notes'),
                Textarea::make('Стратегия (RU)', 'strategy_notes_translations->ru'),
                Textarea::make('Стратегия (UZ)', 'strategy_notes_translations->uz'),
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
