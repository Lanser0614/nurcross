<?php

namespace App\MoonShine\Fields;

use MoonShine\UI\Fields\Field;

class Map extends Field
{
    protected string $view = 'moonshine.fields.map';

    protected ?float $lat = null;
    protected ?float $lng = null;

    public function lat(?float $lat): static
    {
        $this->lat = $lat;
        return $this;
    }

    public function lng(?float $lng): static
    {
        $this->lng = $lng;
        return $this;
    }

    // Данные, которые попадут в Blade
    protected function viewData(): array
    {
        return [
            'lat' => $this->lat,
            'lng' => $this->lng,
        ];
    }
}
