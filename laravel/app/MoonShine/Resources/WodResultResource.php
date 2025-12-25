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
#[Group('Athlete Logs')]
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
        return 'WOD Results';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('WOD', 'wod.title')->sortable(),
            Text::make('Athlete', 'user.name')->sortable(),
            Text::make('Score', 'score_display'),
            Switcher::make('RX', 'is_rx'),
            Date::make('Performed', 'performed_at')->withTime()->sortable(),
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
                BelongsTo::make('Athlete', 'user', resource: UserResource::class)
                    ->required()
                    ->searchable(),
                BelongsTo::make('Gym', 'gym', resource: GymResource::class)
                    ->nullable()
                    ->searchable(),
                Number::make('Time (sec)', 'time_in_seconds'),
                Number::make('Total reps', 'total_reps'),
                Number::make('Weight (kg)', 'weight_in_kg')->step(0.5),
                Switcher::make('RX', 'is_rx'),
                Select::make('Scale', 'result_scale')
                    ->options([
                        'rx' => 'RX',
                        'scaled' => 'Scaled',
                        'modified' => 'Modified',
                    ])
                    ->required(),
                Text::make('Score display', 'score_display'),
                Date::make('Performed at', 'performed_at')->withTime(),
                Textarea::make('Notes', 'notes'),
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
