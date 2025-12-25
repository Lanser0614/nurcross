<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Gym;
use App\MoonShine\Fields\Map;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\Support\Attributes\Icon;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

#[Icon('building-office-2')]
#[Group('Catalog')]
/**
 * @extends ModelResource<Gym>
 */
class GymResource extends ModelResource
{
    protected string $model = Gym::class;

    protected string $column = 'name';

    public function title(): string
    {
        return 'Gyms';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Name', 'name')->sortable(),
            Text::make('City', 'city')->sortable(),
            Text::make('Type', 'type')->badge(),
            Text::make('Phone', 'phone')->copy(),
        ];
    }

    protected function beforeCreating(mixed $item): mixed
    {
//        $item->slug = str_replace(' ', '-', strtolower($this->getItem()->name));
        return $item;
    }

    protected function beforeUpdating(mixed $item): mixed
    {
        $item->slug = str_replace(' ', '-', strtolower($item->name));
        return $item;
    }

    protected function formFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Name', 'name')->required(),
            Text::make('Slug', 'slug'),
            Text::make('City', 'city')->required(),
            Text::make('Type', 'type')->required(),
            Textarea::make('Description', 'description'),
            Text::make('Address', 'address'),
            Text::make('Phone', 'phone'),
            Text::make('Email', 'email'),
            Text::make('Website', 'website'),
            Text::make('Instagram', 'instagram')->hint('@username'),
            Text::make('Telegram', 'telegram')->hint('@username'),
//                Number::make('Latitude', 'latitude'),
//                Number::make('Longitude', 'longitude'),
            Number::make('Latitude', 'latitude')->step(0.000001)->required(),
            Number::make('Longitude', 'longitude')->step(0.000001)->required(),

//            Map::make('Location', 'location')
            Map::make('Location')
                ->lat($this->getItem()?->latitude)
                ->lng($this->getItem()?->longitude),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function rules($item): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
//            'slug' => ['required', 'string', 'max:160', 'unique:gyms,slug,' . $item->getKey()],
            'city' => ['required', 'string', 'max:120'],
            'type' => ['nullable', 'string', 'max:80'],
            'email' => ['nullable', 'email'],
            'website' => ['nullable', 'url'],
        ];
    }

    protected function search(): array
    {
        return ['name', 'city', 'type'];
    }
}
