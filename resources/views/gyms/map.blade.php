@extends('layouts.app')

@section('title', __('Gyms map'))

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        <div class="flex flex-col gap-4">
            <div>
                <p class="text-sm uppercase tracking-wide text-orange-400 font-semibold">{{ __('Gyms') }}</p>
                <h1 class="text-3xl sm:text-4xl font-bold text-white">{{ __('Gyms map') }}</h1>
                <p class="text-gray-400 mt-2 max-w-2xl">
                    {{ __('Explore every box on the map and drop a pin to find the closest pickup location.') }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-300">
                <span class="px-3 py-1 rounded-full border border-slate-700">
                    {{ __(':count gyms pinned', ['count' => $gyms->count()]) }}
                </span>
                <a href="{{ route('gyms.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-500/10 text-orange-300 border border-orange-500/40 hover:bg-orange-500 hover:text-black transition text-sm font-semibold">
                    {{ __('Open list view') }}
                </a>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div id="gyms-map" class="h-[500px] sm:h-[620px] rounded-3xl border border-slate-800 overflow-hidden shadow-2xl shadow-orange-500/5"></div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const googleMapsLabel = @json(__('Open in Google Maps'));

        document.addEventListener('DOMContentLoaded', () => {
            const gyms = {!! $gymsJson !!};
            const mapElement = document.getElementById('gyms-map');

            if (! mapElement) {
                return;
            }

            const defaultCenter = [41.2995, 69.2401];
            const map = L.map(mapElement).setView(defaultCenter, 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(map);

            const bounds = [];

            gyms.forEach((gym) => {
                if (! gym.latitude || ! gym.longitude) {
                    return;
                }

                const coords = [parseFloat(gym.latitude), parseFloat(gym.longitude)];
                bounds.push(coords);

                const mapsUrl = `https://www.google.com/maps?q=${coords[0]},${coords[1]}`;

                L.marker(coords).addTo(map).bindPopup(`
                    <div class="space-y-1">
                        <p class="font-semibold text-sm text-orange-500">${gym.name}</p>
                        <p class="text-xs text-slate-600">${gym.city ?? ''}</p>
                        <p class="text-xs text-slate-500">${gym.address ?? ''}</p>
                        ${gym.phone ? `<p class="text-xs text-slate-400">📞 ${gym.phone}</p>` : ''}
                        ${gym.website ? `<a href="${gym.website}" target="_blank" class="text-xs text-orange-500 underline">Website</a>` : ''}
                        ${googleMapsLabel ? `<a href="${mapsUrl}" target="_blank" rel="noopener noreferrer" class="text-xs text-orange-500 underline">${googleMapsLabel}</a>` : ''}
                    </div>
                `);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [50, 50] });
            } else {
                map.setView(defaultCenter, 5);
            }
        });
    </script>
@endpush
