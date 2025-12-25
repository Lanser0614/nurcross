<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Resources\CoachResource;
use App\MoonShine\Resources\GymResource;
use App\MoonShine\Resources\MovementResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\WodResource;
use App\MoonShine\Resources\WodResultResource;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use MoonShine\UI\Components\{Breadcrumbs,
    Components,
    Layout\Flash,
    Layout\Div,
    Layout\Body,
    Layout\Burger,
    Layout\Content,
    Layout\Footer,
    Layout\Head,
    Layout\Favicon,
    Layout\Assets,
    Layout\Meta,
    Layout\Header,
    Layout\Html,
    Layout\Layout,
    Layout\Logo,
    Layout\Menu,
    Layout\Sidebar,
    Layout\ThemeSwitcher,
    Layout\TopBar,
    Layout\Wrapper,
    When};

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make(__('moonshine::ui.resource.system'), [
                MenuItem::make(__('moonshine::ui.resource.admins_title'), \App\MoonShine\Resources\MoonShineUserResource::class),
                MenuItem::make(__('moonshine::ui.resource.role_title'), \App\MoonShine\Resources\MoonShineUserRoleResource::class),
            ]),
            MenuGroup::make('Catalog', [
                MenuItem::make('Gyms', GymResource::class)->icon('building-office-2'),
                MenuItem::make('Coaches', CoachResource::class)->icon('user-group'),
                MenuItem::make('Movements', MovementResource::class)->icon('bolt'),
            ]),
            MenuGroup::make('Programming', [
                MenuItem::make('WODs', WodResource::class)->icon('fire'),
            ]),
            MenuGroup::make('Athlete Logs', [
                MenuItem::make('Results', WodResultResource::class)->icon('clipboard-document-check'),
                MenuItem::make('Athletes', UserResource::class)->icon('user-circle'),
            ]),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
