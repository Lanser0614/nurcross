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
#[Group('Catalog')]
/**
 * @extends ModelResource<Coach>
 */
class CoachResource extends ModelResource
{
    protected string $model = Coach::class;

    protected string $column = 'full_name';

    public function title(): string
    {
        return 'Coaches';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Name', 'full_name')->sortable(),
            Text::make('Gym', 'gym.name')->sortable(),
            Text::make('Role', 'role'),
            Switcher::make('Active', 'is_active'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make('Gym', 'gym', resource: GymResource::class)->required(),
                Text::make('Full name', 'full_name')->required(),
                Text::make('Slug', 'slug')->required(),
                Text::make('Role', 'role'),
                Text::make('Specialties', 'specialties')->hint('Comma separated'),
                Text::make('Certifications', 'certifications')->hint('Comma separated'),
                Text::make('Phone', 'phone'),
                Text::make('Email', 'email'),
                Text::make('Instagram', 'instagram')->hint('@username'),
                Text::make('Photo URL', 'photo_url'),
                Switcher::make('Active', 'is_active'),
                Textarea::make('Bio', 'bio'),
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
