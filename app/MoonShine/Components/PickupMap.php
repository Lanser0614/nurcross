<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use MoonShine\UI\Components\MoonShineComponent;

class PickupMap extends MoonShineComponent
{
    protected string $view = 'moonshine::components.pickup-map';

    public function __construct(
        protected string $latField = 'latitude',
        protected string $lngField = 'longitude',
        protected ?float $defaultLat = null,
        protected ?float $defaultLng = null,
    ) {
        parent::__construct('pickup-map');
    }

    protected function viewData(): array
    {
        return [
            'latField' => $this->latField,
            'lngField' => $this->lngField,
            'defaultLat' => $this->defaultLat ?? 41.2995,
            'defaultLng' => $this->defaultLng ?? 69.2401,
        ];
    }

}
