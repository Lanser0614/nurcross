@extends('layouts.app')

@section('title', __('text.CrossFit Events'))

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-950 to-black">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">
            <div class="max-w-3xl">
                <p class="uppercase tracking-[0.4em] text-xs text-orange-300">{{ __('text.CrossFit Uzbekistan') }}</p>
                <h1 class="text-4xl sm:text-5xl font-black mt-4">{{ __('text.CrossFit Events') }}</h1>
                <p class="text-lg text-gray-300 mt-4">
                    {{ __('text.Discover competitions, seminars, and meetups across Uzbekistan.') }}
                </p>
            </div>

            <form method="GET" class="grid gap-4 md:grid-cols-3 bg-slate-900/70 border border-slate-800 rounded-3xl p-6 text-sm">
                <div class="flex flex-col gap-2">
                    <label for="category" class="text-gray-400">{{ __('text.Category') }}</label>
                    <select id="category" name="category" class="bg-slate-950 border border-slate-800 rounded-2xl px-3 py-2">
                        <option value="">{{ __('text.All categories') }}</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['category'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="gym" class="text-gray-400">{{ __('text.Gym') }}</label>
                    <select id="gym" name="gym" class="bg-slate-950 border border-slate-800 rounded-2xl px-3 py-2">
                        <option value="">{{ __('text.All gyms') }}</option>
                        @foreach($gyms as $id => $gymName)
                            <option value="{{ $id }}" @selected(($filters['gym'] ?? '') == $id)>{{ $gymName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end justify-between gap-4 md:flex-col md:items-start">
                    <label class="inline-flex items-center gap-2 text-gray-400">
                        <input type="checkbox" name="include_past" value="1" @checked(! empty($filters['include_past'])) class="rounded border-slate-700 bg-slate-900 text-orange-500 focus:ring-orange-500">
                        <span>{{ __('text.Include past events') }}</span>
                    </label>
                    <button type="submit" class="px-5 py-2 rounded-2xl bg-orange-500 text-black font-semibold shadow shadow-orange-500/30">
                        {{ __('text.Filter') }}
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-10">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($events as $event)
                <div class="bg-slate-900/70 border border-slate-800 rounded-3xl p-6 flex flex-col gap-4 hover:border-orange-500/70 transition">
                    <div class="flex items-center justify-between gap-2 text-xs uppercase tracking-[0.35em] text-gray-400">
                        <span>{{ __('text.Starts at') }} {{ $event->start_at->translatedFormat('d M Y, H:i') }}</span>
                        <span class="px-3 py-1 rounded-full bg-slate-800 text-gray-200 tracking-[0.25em] text-[0.6rem]">
                            {{ $event->category->label() }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold">{{ $event->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            @if($event->gym)
                                {{ __('text.Hosted at :name', ['name' => $event->gym->name]) }}
                            @elseif($event->city)
                                {{ $event->city }}
                            @endif
                        </p>
                    </div>
                    <p class="text-sm text-gray-300 line-clamp-4">{{ $event->description_localized }}</p>
                    <div class="text-xs text-gray-500 space-y-1">
                        @if($event->city)
                            <p>{{ __('text.City') }}: {{ $event->city }}</p>
                        @endif
                        @if($event->address)
                            <p>{{ __('text.Address') }}: {{ $event->address }}</p>
                        @endif
                        @if($event->end_at)
                            <p>{{ __('text.Ends') }}: {{ $event->end_at->translatedFormat('d M Y, H:i') }}</p>
                        @endif
                    </div>
                    @if($event->registration_url)
                        <a href="{{ $event->registration_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center px-4 py-2 rounded-2xl border border-orange-500 text-orange-300 text-sm hover:bg-orange-500 hover:text-black transition">
                            {{ __('text.Registration') }}
                        </a>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-slate-900/50 border border-dashed border-slate-700 rounded-3xl">
                    <p class="text-gray-400">{{ __('text.No events available yet.') }}</p>
                </div>
            @endforelse
        </div>

        <div>
            {{ $events->links() }}
        </div>
    </section>
@endsection
