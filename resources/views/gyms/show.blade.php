@extends('layouts.app')

@section('title', $gym->name . ' · ' . __('CrossFit Uzbekistan'))

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        <div class="bg-slate-900/70 border border-slate-800 rounded-3xl p-8 flex flex-col gap-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $gym->city }}</p>
                    <h1 class="text-4xl font-bold mt-2">{{ $gym->name }}</h1>
                    <p class="text-gray-400 mt-4 max-w-2xl">{{ $gym->description }}</p>
                </div>
                <div class="flex flex-wrap gap-3 text-sm text-gray-300">
                    <span class="px-3 py-1 rounded-full bg-slate-950 border border-slate-800">{{ ucfirst($gym->type) }}</span>
                    @if($gym->phone)
                    <span class="px-3 py-1 rounded-full bg-slate-950 border border-slate-800">☎ {{ $gym->phone }}</span>
                    @endif
                    @if($gym->instagram)
                        <span class="px-3 py-1 rounded-full bg-slate-950 border border-slate-800">IG: {{ '@'.$gym->instagram }}</span>
                    @endif
                </div>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-500">{{ __('Location') }}</p>
                    <p class="text-lg font-semibold mt-2">{{ $gym->address ?? __('Details coming soon') }}</p>
                    <p class="text-gray-500 text-sm mt-2">
                        Lat {{ $gym->latitude ?? '—' }} / Long {{ $gym->longitude ?? '—' }}
                    </p>
                </div>
                <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-500">{{ __('Contacts') }}</p>
                    <ul class="mt-3 space-y-2 text-sm text-gray-300">
                        <li>{{ __('Email') }}: {{ $gym->email ?? '—' }}</li>
                        <li>{{ __('Website') }}: {{ $gym->website ?? '—' }}</li>
                        <li>{{ __('Telegram') }}: {{ $gym->telegram ?? '—' }}</li>
                    </ul>
                </div>
                <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between">
                    <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-500">{{ __('Coaches') }}</p>
                        <p class="text-3xl font-bold mt-3">{{ $gym->coaches->count() }}</p>
                    </div>
                    <a href="#coaches" class="text-orange-400 text-sm hover:underline">{{ __('Meet the team') }}</a>
                </div>
            </div>
            @if($gym->latitude && $gym->longitude)
                <div class="rounded-2xl border border-slate-800 overflow-hidden shadow-inner shadow-black/30">
                    <div
                        id="gym-map"
                        data-lat="{{ $gym->latitude }}"
                        data-lng="{{ $gym->longitude }}"
                        data-name="{{ $gym->name }}"
                        data-address="{{ $gym->address }}"
                        data-city="{{ $gym->city }}"
                        class="h-64 md:h-80 w-full"
                    ></div>
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-800 h-64 flex items-center justify-center text-gray-500 text-sm uppercase tracking-[0.4em]">
                    {{ __('Map placeholder (coming soon)') }}
                </div>
            @endif
        </div>

        <section id="coaches">
            <h2 class="text-2xl font-bold mb-4">{{ __('Coaches') }}</h2>
            <div class="grid gap-6 md:grid-cols-2">
                @forelse($gym->coaches as $coach)
                    <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 flex gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-center text-lg font-bold">
                            {{ collect(explode(' ', $coach->full_name))->map(fn ($part) => substr($part, 0, 1))->take(2)->join('') }}
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-widest text-gray-500">{{ $coach->role ?? 'Coach' }}</p>
                            <h3 class="text-xl font-semibold mt-1">{{ $coach->full_name }}</h3>
                            <p class="text-gray-400 text-sm mt-2 line-clamp-3">{{ $coach->bio }}</p>
                            <p class="text-xs text-gray-500 mt-2">Specialties: {{ $coach->specialties ?? 'Functional fitness' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400">{{ __('No coaches listed yet.') }}</p>
                @endforelse
            </div>
        </section>

        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">{{ __('Latest WODs from :name', ['name' => $gym->name]) }}</h2>
                <a href="{{ route('wods.index') }}" class="text-sm text-orange-400 hover:underline">{{ __('All WODs') }}</a>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                @forelse($gym->wods as $wod)
                    <a href="{{ route('wods.show', $wod) }}" class="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 hover:border-orange-500/60 transition">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold">{{ $wod->title }}</h3>
                            <span class="text-xs px-3 py-1 rounded-full bg-slate-800 uppercase tracking-[0.3em]">{{ $wod->type_translated }}</span>
                        </div>
                        <p class="text-gray-400 text-sm mt-3 whitespace-pre-line line-clamp-3">{{ $wod->description_localized }}</p>
                        <div class="flex flex-wrap gap-2 text-xs text-gray-400 mt-4">
                            @foreach($wod->movements->take(3) as $movement)
                                <span class="px-2 py-1 rounded-full bg-slate-950 border border-slate-800">{{ $movement->name }}</span>
                            @endforeach
                        </div>
                    </a>
                @empty
                    <p class="text-gray-400">{{ __('No workouts published yet.') }}</p>
                @endforelse
            </div>
        </section>
    </section>
@endsection

@if($gym->latitude && $gym->longitude)
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            const googleMapsLabel = @json(__('Open in Google Maps'));

            document.addEventListener('DOMContentLoaded', () => {
                const mapElement = document.getElementById('gym-map');

                if (! mapElement || typeof L === 'undefined') {
                    return;
                }

                const lat = parseFloat(mapElement.dataset.lat);
                const lng = parseFloat(mapElement.dataset.lng);

                if (Number.isNaN(lat) || Number.isNaN(lng)) {
                    return;
                }

                const map = L.map(mapElement).setView([lat, lng], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                }).addTo(map);

                const marker = L.marker([lat, lng]).addTo(map);
                const mapUrl = `https://www.google.com/maps?q=${lat},${lng}`;
                const popupLines = [];

                if (mapElement.dataset.name) {
                    popupLines.push(`<p class="font-semibold text-sm text-orange-500">${mapElement.dataset.name}</p>`);
                }

                if (mapElement.dataset.city) {
                    popupLines.push(`<p class="text-xs text-slate-400">${mapElement.dataset.city}</p>`);
                }

                if (mapElement.dataset.address) {
                    popupLines.push(`<p class="text-xs text-slate-500">${mapElement.dataset.address}</p>`);
                }

                if (googleMapsLabel && mapUrl) {
                    popupLines.push(`<a href="${mapUrl}" target="_blank" rel="noopener noreferrer" class="text-xs text-orange-500 underline">${googleMapsLabel}</a>`);
                }

                if (popupLines.length) {
                    marker.bindPopup(`<div class="space-y-1">${popupLines.join('')}</div>`);
                }
            });
        </script>
    @endpush
@endif
