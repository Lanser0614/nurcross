<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Movement;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\Support\Attributes\Icon;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

#[Icon('bolt')]
#[Group('Catalog')]
/**
 * @extends ModelResource<Movement>
 */
class MovementResource extends ModelResource
{
    protected string $model = Movement::class;

    protected string $column = 'name';

    public function title(): string
    {
        return 'Movements';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Name', 'name')->sortable(),
            Text::make('Category', 'category')->badge(),
            Text::make('Difficulty', 'difficulty')->badge(),
            Text::make('Equipment', 'equipment'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Name (EN)', 'name')->required(),
                Text::make('Name (RU)', 'name_ru'),
                Text::make('Slug', 'slug')->required(),
                Select::make('Category', 'category')
                    ->options([
                        'weightlifting' => 'Weightlifting',
                        'gymnastics' => 'Gymnastics',
                        'monostructural' => 'Monostructural',
                    ])
                    ->searchable()
                    ->required(),
                Select::make('Difficulty', 'difficulty')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ])
                    ->required(),
                Text::make('Equipment', 'equipment'),
                Text::make('Thumbnail URL', 'thumbnail_url'),
                Text::make('YouTube / playlist', 'youtube_url'),
                Textarea::make('Description (EN)', 'description'),
                Textarea::make('Description (RU)', 'description_translations->ru'),
                Textarea::make('Description (UZ)', 'description_translations->uz'),
                Textarea::make('Technique (EN)', 'technique_notes'),
                Textarea::make('Technique (RU)', 'technique_notes_translations->ru'),
                Textarea::make('Technique (UZ)', 'technique_notes_translations->uz'),
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function rules($item): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:160', 'unique:movements,slug,' . $item->getKey()],
            'category' => ['required', 'in:weightlifting,gymnastics,monostructural'],
            'difficulty' => ['required', 'in:beginner,intermediate,advanced'],
            'youtube_url' => ['nullable', 'url'],
        ];
    }

    protected function search(): array
    {
        return ['name', 'name_ru', 'slug', 'category'];
    }
}
