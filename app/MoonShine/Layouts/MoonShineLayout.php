<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Resources\CoachResource;
use App\MoonShine\Resources\GymResource;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\MovementResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\WodResource;
use App\MoonShine\Resources\WodResultResource;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use MoonShine\UI\Components\{Breadcrumbs,
    Layout\Layout,
    When
};

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
                MenuItem::make(MoonShineUserResource::class, __('moonshine::ui.resource.admins_title')),
                MenuItem::make(MoonShineUserRoleResource::class, __('moonshine::ui.resource.role_title')),
            ]),
            MenuGroup::make('Каталог', [
                MenuItem::make(GymResource::class, 'Залы')->icon('building-office-2'),
                MenuItem::make(CoachResource::class, 'Тренеры')->icon('user-group'),
                MenuItem::make(MovementResource::class, 'Упражнения')->icon('bolt'),
            ]),
            MenuGroup::make('Программинг', [
                MenuItem::make(WodResource::class, 'WODы')->icon('fire'),
            ]),
            MenuGroup::make('Журналы атлетов', [
                MenuItem::make(WodResultResource::class, 'Результаты')->icon('clipboard-document-check'),
                MenuItem::make(UserResource::class, 'Атлеты')->icon('user-circle'),
            ]),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
