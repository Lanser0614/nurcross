<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Enums\EventCategory;
use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Attributes\Icon;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

#[Icon('calendar-days')]
/**
 * @extends ModelResource<Event>
 */
class EventResource extends ModelResource
{
    protected string $model = Event::class;

    protected string $column = 'title';

    protected array $with = ['gym'];


    public function title(): string
    {
        return 'События';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'title')->sortable(),
            Text::make('Категория', 'category')
                ->badge()
                ->changePreview(function (EventCategory $value): string {
                    return $value->label();
                }),
            Date::make('Дата начала', 'start_at')->withTime(),
            Date::make('Дата окончания', 'end_at')->withTime(),
            Text::make('Зал', 'gym.name')->sortable(),
            Switcher::make('Избранное', 'is_featured'),
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
                Text::make('Название', 'title')
                    ->required(),
                Text::make('Слаг', 'slug')
                    ->hint('Оставьте пустым, чтобы сгенерировать автоматически'),
                Select::make('Категория', 'category')
                    ->required()
                    ->options(EventCategory::options()),
                Date::make('Дата начала', 'start_at')
                    ->withTime()
                    ->required(),
                Date::make('Дата окончания', 'end_at')
                    ->withTime(),
                Text::make('Город', 'city'),
                Text::make('Адрес', 'address'),
                Text::make('Ссылка на регистрацию', 'registration_url'),
                Switcher::make('Избранное', 'is_featured'),
                Textarea::make('Desc', 'description'),
                File::make('Видео события', 'content_video_path')
                    ->disk('public')
                    ->dir('event-content')
                    ->allowedExtensions(['mp4', 'mov', 'webm'])
                    ->hint('MP4, MOV или WEBM до 100 МБ'),
            ]),
        ];
    }

    /**
     * @param Event $item
     * @return array<string, mixed>
     */
    public function rules($item): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:events,slug,' . $item->getKey()],
            'category' => ['required', 'in:' . implode(',', array_map(static fn(EventCategory $case) => $case->value, EventCategory::cases()))],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
        ];
    }

    protected function search(): array
    {
        return ['title', 'city', 'category'];
    }
}
