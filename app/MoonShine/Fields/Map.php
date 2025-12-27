<?php

namespace App\MoonShine\Fields;

use Closure;
use Illuminate\Support\Facades\Route;
use MoonShine\UI\Fields\Field;

class Map extends Field
{
    protected string $view = 'moonshine.fields.map';

    protected ?float $lat = null;
    protected ?float $lng = null;

    protected string $latName = 'latitude';
    protected string $lngName = 'longitude';

    protected ?string $manualSaveUrl = null;

    public function __construct(
        Closure|string|null $label = null,
        ?string $column = null,
        ?Closure $formatted = null,
    ) {
        parent::__construct($label, $column, $formatted);

        $this->setColumn($column ?? $this->latName);

        $this->onBeforeApply(function (mixed $data): mixed {
            return $this->syncCoordinatesFromRequest($data);
        });
    }

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

    public function latField(string $column): static
    {
        $this->latName = $column;
        $this->setColumn($column);

        return $this;
    }

    public function lngField(string $column): static
    {
        $this->lngName = $column;

        return $this;
    }

    public function coordinates(string $latColumn, string $lngColumn): static
    {
        return $this->latField($latColumn)->lngField($lngColumn);
    }

    public function saveUrl(?string $url): static
    {
        $this->manualSaveUrl = $url;

        return $this;
    }

    public function fillData(mixed $value, int $index = 0): static
    {
        parent::fillData($value, $index);

        if ($this->getData()) {
            $original = $this->getData()?->getOriginal();

            $this->lat ??= data_get($original, $this->latName);
            $this->lng ??= data_get($original, $this->lngName);
        }

        return $this;
    }

    protected function syncCoordinatesFromRequest(mixed $item): mixed
    {
        $request = $this->getCore()->getRequest();

        $latPresent = $request->has($this->latName);
        $lngPresent = $request->has($this->lngName);

        if (! $latPresent && ! $lngPresent) {
            return $item;
        }

        if ($latPresent) {
            data_set($item, $this->latName, $this->resolveCoordinate($request->input($this->latName)));
        }

        if ($lngPresent) {
            data_set($item, $this->lngName, $this->resolveCoordinate($request->input($this->lngName)));
        }

        return $item;
    }

    protected function resolveCoordinate(mixed $value): ?float
    {
        if ($value === '' || \is_null($value)) {
            return null;
        }

        return round((float) $value, 6);
    }

    protected function resolveSaveUrl(): ?string
    {
        if ($this->manualSaveUrl !== null) {
            return $this->manualSaveUrl;
        }

        $data = $this->getData();

        if ($data === null || $data->getKey() === null) {
            return null;
        }

        $routeName = 'moonshine.gyms.coordinates';

        if (! Route::has($routeName)) {
            return null;
        }

        return route($routeName, ['gym' => $data->getKey()]);
    }

    protected function viewData(): array
    {
        return [
            'lat' => $this->lat,
            'lng' => $this->lng,
            'latName' => $this->latName,
            'lngName' => $this->lngName,
            'saveUrl' => $this->resolveSaveUrl(),
        ];
    }
}
