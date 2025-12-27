<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Contracts\Core\ResourceContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\CoachResource;
use App\MoonShine\Resources\GymResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MovementResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\WodResource;
use App\MoonShine\Resources\WodResultResource;
use MoonShine\Laravel\Exceptions\MoonShineNotFoundException;
use MoonShine\Support\Enums\Ability;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        $config
            ->title('My Application')
            ->dir('app/MoonShine', 'App\MoonShine')
            ->homeRoute('moonshine.index')
            ->notFoundException(MoonShineNotFoundException::class)
            ->disk('public')
//            ->cacheDriver('redis')
            ->guard('moonshine')
            ->authPipelines([])
            ->authorizationRules(
                function(ResourceContract $ctx, mixed $user, Ability $ability, mixed $data): bool {
                    return true;
                }
            )
            ->locale('ru')
            ->locales(['en', 'ru']);


        $core
            ->resources([
                GymResource::class,
                CoachResource::class,
                MovementResource::class,
                WodResource::class,
                WodResultResource::class,
                UserResource::class,
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
            ]);
    }
}
