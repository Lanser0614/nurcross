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
#[Group('Каталог')]
/**
 * @extends ModelResource<Movement>
 */
class MovementResource extends ModelResource
{
    protected string $model = Movement::class;

    protected string $column = 'name';

    public function title(): string
    {
        return 'Упражнения';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
            Text::make('Категория', 'category')->badge(),
            Text::make('Сложность', 'difficulty')->badge(),
            Text::make('Оборудование', 'equipment'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Название (EN)', 'name')->required(),
                Text::make('Название (RU)', 'name_ru'),
                Text::make('Слаг', 'slug')->required(),
                Select::make('Категория', 'category')
                    ->options([
                        'weightlifting' => 'Тяжёлая атлетика',
                        'gymnastics' => 'Гимнастика',
                        'monostructural' => 'Моноструктурные',
                    ])
                    ->searchable()
                    ->required(),
                Select::make('Сложность', 'difficulty')
                    ->options([
                        'beginner' => 'Новичок',
                        'intermediate' => 'Средний',
                        'advanced' => 'Продвинутый',
                    ])
                    ->required(),
                Text::make('Оборудование', 'equipment'),
                Text::make('URL превью', 'thumbnail_url'),
                Text::make('YouTube / плейлист', 'youtube_url'),
                Textarea::make('Описание (EN)', 'description'),
                Textarea::make('Описание (RU)', 'description_translations->ru'),
                Textarea::make('Описание (UZ)', 'description_translations->uz'),
                Textarea::make('Техника (EN)', 'technique_notes'),
                Textarea::make('Техника (RU)', 'technique_notes_translations->ru'),
                Textarea::make('Техника (UZ)', 'technique_notes_translations->uz'),
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
