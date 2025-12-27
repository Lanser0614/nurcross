@php($mapId = 'pickup-map-' . uniqid())

<div
    class="w-full"
    x-data="pickupMap_{{ str_replace('-', '_', $mapId) }}({
        latFieldName: '{{ $latField }}',
        lngFieldName: '{{ $lngField }}',
        defaultLat: {{ $defaultLat ?? 41.311081 }},
        defaultLng: {{ $defaultLng ?? 69.240562 }},
        zoom: 13
    })"
>
    {{-- Контейнер карты --}}
    <div
        id="{{ $mapId }}"
        x-ref="map"
        class="pickup-map w-full rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden relative z-0"
        style="height: 400px; min-height: 400px;"
    ></div>

    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
        {{ __('Click on the map to update coordinates.') }}
    </p>
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
        <style>
            /* Z-Index фикс для MoonShine */
            .pickup-map .leaflet-pane { z-index: 10 !important; }
            .pickup-map .leaflet-top,
            .pickup-map .leaflet-bottom { z-index: 11 !important; }
            .pickup-map .leaflet-control-attribution { display: none; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                // Динамическое имя компонента нужно, если на странице несколько карт
                // Но можно использовать и одно общее имя pickupMap
                const initPickupMap = (config) => ({
                    map: null,
                    marker: null,
                    latInput: null,
                    lngInput: null,

                    init() {
                        this.$nextTick(() => {
                            this.initMap();
                        });
                    },

                    initMap() {
                        if (typeof L === 'undefined') return;

                        // Ищем инпуты по имени (name attribute)
                        this.latInput = document.querySelector(`[name="${config.latFieldName}"]`);
                        this.lngInput = document.querySelector(`[name="${config.lngFieldName}"]`);

                        const initialLat = (this.latInput && this.latInput.value)
                            ? parseFloat(this.latInput.value)
                            : config.defaultLat;

                        const initialLng = (this.lngInput && this.lngInput.value)
                            ? parseFloat(this.lngInput.value)
                            : config.defaultLng;

                        this.fixLeafletIcons();

                        this.map = L.map(this.$refs.map).setView([initialLat, initialLng], config.zoom);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                        }).addTo(this.map);

                        this.marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(this.map);

                        // События обновления
                        this.marker.on('moveend', (e) => this.updateInputs(e.target.getLatLng()));
                        this.map.on('click', (e) => {
                            this.marker.setLatLng(e.latlng);
                            this.updateInputs(e.latlng);
                        });

                        // ГЛАВНЫЙ ФИКС: ResizeObserver
                        const resizeObserver = new ResizeObserver(() => {
                            this.map.invalidateSize();
                        });
                        resizeObserver.observe(this.$refs.map);
                    },

                    updateInputs(latlng) {
                        if (this.latInput) {
                            this.latInput.value = latlng.lat.toFixed(6);
                            this.latInput.dispatchEvent(new Event('input'));
                        }
                        if (this.lngInput) {
                            this.lngInput.value = latlng.lng.toFixed(6);
                            this.lngInput.dispatchEvent(new Event('input'));
                        }
                    },

                    fixLeafletIcons() {
                        if (window.__leafletIconsFixed) return;
                        const iconBase = 'https://unpkg.com/leaflet@1.9.4/dist/images/';
                        delete L.Icon.Default.prototype._getIconUrl;
                        L.Icon.Default.mergeOptions({
                            iconRetinaUrl: iconBase + 'marker-icon-2x.png',
                            iconUrl: iconBase + 'marker-icon.png',
                            shadowUrl: iconBase + 'marker-shadow.png',
                        });
                        window.__leafletIconsFixed = true;
                    }
                });

                // Регистрируем компонент глобально для Alpine
                // Используем regex, чтобы регистрировать динамически или просто одну функцию
                window.Alpine.data('pickupMap_{{ str_replace('-', '_', $mapId) }}', initPickupMap);
            });
        </script>
    @endpush
@endonce
