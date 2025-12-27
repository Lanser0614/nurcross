<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Coach;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\Support\Attributes\Icon;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

#[Icon('user-group')]
#[Group('Каталог')]
/**
 * @extends ModelResource<Coach>
 */
class CoachResource extends ModelResource
{
    protected string $model = Coach::class;

    protected string $column = 'full_name';

    public function title(): string
    {
        return 'Тренеры';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'full_name')->sortable(),
            Text::make('Зал', 'gym.name')->sortable(),
            Text::make('Роль', 'role'),
            Switcher::make('Активен', 'is_active'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make('Зал', 'gym', resource: GymResource::class)->required(),
                Text::make('Полное имя', 'full_name')->required(),
                Text::make('Слаг', 'slug')->required(),
                Text::make('Роль', 'role'),
                Text::make('Специализации', 'specialties')->hint('Через запятую'),
                Text::make('Сертификаты', 'certifications')->hint('Через запятую'),
                Text::make('Телефон', 'phone'),
                Text::make('E-mail', 'email'),
                Text::make('Instagram', 'instagram')->hint('@username'),
                Text::make('URL фото', 'photo_url'),
                Switcher::make('Активен', 'is_active'),
                Textarea::make('Био', 'bio'),
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function rules($item): array
    {
        return [
            'gym_id' => ['required', 'exists:gyms,id'],
            'full_name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:160', 'unique:coaches,slug,' . $item->getKey()],
        ];
    }

    protected function search(): array
    {
        return ['full_name', 'role'];
    }
}
