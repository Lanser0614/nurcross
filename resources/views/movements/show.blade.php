@extends('layouts.app')

@section('title', $movement->name . ' · ' . __('text.Movement library'))

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-8 space-y-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-500">{{ $movement->category }}</p>
                    <h1 class="text-4xl font-bold mt-2">{{ $movement->name }}</h1>
                    @if($movement->name_ru)
                        <p class="text-gray-400 text-sm">RU: {{ $movement->name_ru }}</p>
                    @endif
                </div>
                <div class="flex flex-wrap gap-3 text-sm text-gray-300">
                    <span class="px-3 py-1 rounded-full bg-slate-950 border border-slate-800">{{ ucfirst($movement->difficulty) }}</span>
                    @if($movement->equipment)
                        <span class="px-3 py-1 rounded-full bg-slate-950 border border-slate-800">{{ __('text.Equipment: :equipment', ['equipment' => $movement->equipment]) }}</span>
                    @endif
                </div>
            </div>

            <p class="text-gray-300 leading-relaxed">{{ $movement->description_localized }}</p>
            <p class="text-sm text-gray-400 whitespace-pre-line">{{ $movement->technique_notes_localized }}</p>
        </div>

        @php
            $videoId = null;
            $playlistId = null;
            $playlistIndex = null;

            if ($movement->youtube_url) {
                $parts = parse_url($movement->youtube_url);
                $queryString = $parts['query'] ?? null;

                if ($queryString) {
                    parse_str($queryString, $params);
                    $videoId = $params['v'] ?? null;
                    $playlistId = $params['list'] ?? null;
                    $playlistIndex = $params['index'] ?? null;
                }

                if (! $videoId && ! empty($parts['path'])) {
                    $segments = explode('/', trim($parts['path'], '/'));
                    $possibleVideo = \Illuminate\Support\Arr::last($segments);
                    if (strlen($possibleVideo) === 11) {
                        $videoId = $possibleVideo;
                    }
                }
            }

            $embedUrl = null;
            $query = [];

            if ($videoId) {
                $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                if ($playlistId) {
                    $query['list'] = $playlistId;
                }
                if ($playlistIndex) {
                    $query['index'] = $playlistIndex;
                }
            } elseif ($playlistId) {
                $embedUrl = "https://www.youtube.com/embed/videoseries";
                $query['list'] = $playlistId;
                if ($playlistIndex) {
                    $query['index'] = $playlistIndex;
                }
            }

            if ($embedUrl && $query) {
                $embedUrl .= '?' . http_build_query($query);
            }
        @endphp

        @if($embedUrl)
            <div class="rounded-3xl border border-slate-800 overflow-hidden shadow-2xl shadow-orange-500/10">
                <iframe
                    class="w-full aspect-video"
                    src="{{ $embedUrl }}"
                    title="{{ $movement->name }}"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
        @endif

        <section>
                <h2 class="text-2xl font-bold mb-4">{{ __('text.Appears in these WODs') }}</h2>
            <div class="grid gap-4 md:grid-cols-2">
                @forelse($movement->wods as $wod)
                    <a href="{{ route('wods.show', $wod) }}" class="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 hover:border-orange-500/60 transition">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold">{{ $wod->title }}</h3>
                            <span class="text-xs px-3 py-1 rounded-full bg-slate-950 border border-slate-800 uppercase tracking-[0.3em]">{{ $wod->type_translated }}</span>
                        </div>
                    <p class="text-gray-400 text-sm mt-3 line-clamp-3">{{ $wod->description_localized }}</p>
                    </a>
                @empty
                    <p class="text-gray-400">{{ __('text.No workouts linked yet.') }}</p>
                @endforelse
            </div>
        </section>
    </section>
@endsection
