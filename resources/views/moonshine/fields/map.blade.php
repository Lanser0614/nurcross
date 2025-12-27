@php($mapId = 'moonshine-map-' . uniqid())
@php($latInputId = $mapId . '-lat')
@php($lngInputId = $mapId . '-lng')
@php($saveButtonId = $mapId . '-save')
@php($statusId = $mapId . '-status')

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@endonce

<input type="hidden" name="{{ $latName }}" id="{{ $latInputId }}" value="{{ $lat }}">
<input type="hidden" name="{{ $lngName }}" id="{{ $lngInputId }}" value="{{ $lng }}">

<div
    id="{{ $mapId }}"
    class="rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden"
    style="height: 320px; min-height: 320px;"
></div>

@if($saveUrl)
    <div class="mt-3 flex items-center gap-3">
        <button type="button" class="btn btn-primary" id="{{ $saveButtonId }}">
            {{ __('moonshine::ui.save') }}
        </button>
        <span id="{{ $statusId }}" class="text-xs text-slate-500"></span>
    </div>
@endif

<script>
    (() => {
        const container = document.getElementById('{{ $mapId }}');
        const latInput = document.getElementById('{{ $latInputId }}');
        const lngInput = document.getElementById('{{ $lngInputId }}');

        if (! container || ! latInput || ! lngInput) {
            return;
        }

        const defaults = {
            lat: {{ $lat !== null ? (float) $lat : 41.31 }},
            lng: {{ $lng !== null ? (float) $lng : 69.28 }},
        };

        const saveUrl = @json($saveUrl);
        console.log(saveUrl)

        const csrf = @json(csrf_token());

        const init = () => {
            if (container.dataset.initialized || typeof L === 'undefined') {
                return;
            }

            container.dataset.initialized = '1';

            const lat = parseFloat(latInput.value) || defaults.lat;
            const lng = parseFloat(lngInput.value) || defaults.lng;

            const map = L.map(container).setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            const updateInputs = ({ lat, lng }) => {
                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);
                latInput.dispatchEvent(new Event('input'));
                lngInput.dispatchEvent(new Event('input'));
            };

            marker.on('dragend', (event) => updateInputs(event.target.getLatLng()));
            map.on('click', (event) => {
                marker.setLatLng(event.latlng);
                updateInputs(event.latlng);
            });

            if ('ResizeObserver' in window) {
                const resizeObserver = new ResizeObserver(() => map.invalidateSize());
                resizeObserver.observe(container);
            } else {
                setTimeout(() => map.invalidateSize(), 250);
            }

            if (! window.__leafletIconsFixed) {
                const iconBase = 'https://unpkg.com/leaflet@1.9.4/dist/images/';
                delete L.Icon.Default.prototype._getIconUrl;
                L.Icon.Default.mergeOptions({
                    iconRetinaUrl: iconBase + 'marker-icon-2x.png',
                    iconUrl: iconBase + 'marker-icon.png',
                    shadowUrl: iconBase + 'marker-shadow.png',
                });
                window.__leafletIconsFixed = true;
            }

            const saveButton = document.getElementById('{{ $saveButtonId }}');
            const statusEl = document.getElementById('{{ $statusId }}');

            if (saveButton && saveUrl) {
                const setStatus = (message, success = true) => {
                    if (! statusEl) {
                        return;
                    }

                    statusEl.textContent = message;
                    statusEl.classList.toggle('text-green-500', success);
                    statusEl.classList.toggle('text-rose-500', ! success);
                };

                saveButton.addEventListener('click', async () => {
                    setStatus('', true);
                    saveButton.disabled = true;

                    try {
                        const response = await fetch(saveUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({
                                latitude: latInput.value,
                                longitude: lngInput.value,
                            }),
                        });

                        if (! response.ok) {
                            const payload = await response.json().catch(() => ({}));
                            throw new Error(payload.message ?? 'Failed to save coordinates');
                        }

                        setStatus('{{ __('moonshine::ui.saved') }}', true);
                    } catch (error) {
                        setStatus(error.message || 'Error', false);
                    } finally {
                        saveButton.disabled = false;
                    }
                });
            }
        };

        const mount = () => {
            if (typeof L === 'undefined') {
                return;
            }

            init();
        };

        if (document.readyState !== 'complete') {
            document.addEventListener('DOMContentLoaded', mount);
        }

        document.addEventListener('turbo:load', mount);
        mount();
    })();
</script>
