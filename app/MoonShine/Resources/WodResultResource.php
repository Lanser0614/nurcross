<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\WodResult;
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

#[Icon('clipboard-document-check')]
#[Group('Журналы атлетов')]
/**
 * @extends ModelResource<WodResult>
 */
class WodResultResource extends ModelResource
{
    protected string $model = WodResult::class;

    protected string $column = 'score_display';

    protected array $with = ['wod', 'user', 'gym'];

    public function title(): string
    {
        return 'Результаты WOD';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('WOD', 'wod.title')->sortable(),
            Text::make('Атлет', 'user.name')->sortable(),
            Text::make('Результат', 'score_display'),
            Switcher::make('RX', 'is_rx'),
            Date::make('Дата выполнения', 'performed_at')->withTime()->sortable(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make('WOD', 'wod', resource: WodResource::class)
                    ->required()
                    ->searchable(),
                BelongsTo::make('Атлет', 'user', resource: UserResource::class)
                    ->required()
                    ->searchable(),
                BelongsTo::make('Зал', 'gym', resource: GymResource::class)
                    ->nullable()
                    ->searchable(),
                Number::make('Время (сек)', 'time_in_seconds'),
                Number::make('Всего повторений', 'total_reps'),
                Number::make('Вес (кг)', 'weight_in_kg')->step(0.5),
                Switcher::make('RX', 'is_rx'),
                Select::make('Модификация', 'result_scale')
                    ->options([
                        'rx' => 'RX',
                        'scaled' => 'Scaled (упрощённый)',
                        'modified' => 'Модифицированный',
                    ])
                    ->required(),
                Text::make('Формат результата', 'score_display'),
                Date::make('Дата выполнения', 'performed_at')->withTime(),
                Textarea::make('Заметки', 'notes'),
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function rules($item): array
    {
        return [
            'wod_id' => ['required', 'exists:wods,id'],
            'user_id' => ['required', 'exists:users,id'],
            'result_scale' => ['required', 'in:rx,scaled,modified'],
        ];
    }

    protected function search(): array
    {
        return ['score_display', 'notes'];
    }
}
