@extends('layouts.app')

@section('title', __('text.Gyms across Uzbekistan'))

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.4em] text-gray-500">{{ __('text.Boxes & studios') }}</p>
                <h1 class="text-3xl font-bold mt-2">{{ __('text.Find your training ground') }}</h1>
                <p class="text-gray-400 max-w-2xl">{{ __('text.Filter by city or type to locate the perfect CrossFit box or functional training facility.') }}</p>
            </div>
            <form method="GET" class="bg-slate-900/70 border border-slate-800 rounded-2xl p-4 flex flex-col sm:flex-row gap-3 text-sm">
                <div class="flex flex-col">
                    <label for="city" class="text-gray-400 mb-1">{{ __('text.City') }}</label>
                    <select name="city" id="city" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-gray-200">
                        <option value="">{{ __('text.Any') }}</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" @selected(($filters['city'] ?? '') === $city)>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="type" class="text-gray-400 mb-1">{{ __('text.Type') }}</label>
                    <select name="type" id="type" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-gray-200">
                        <option value="">{{ __('text.Any') }}</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" @selected(($filters['type'] ?? '') === $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-orange-500 text-black font-semibold shadow shadow-orange-500/30">
                        {{ __('text.Filter') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            @forelse($gyms as $gym)
                <a href="{{ route('gyms.show', $gym) }}" class="bg-slate-900/70 border border-slate-800 rounded-3xl p-6 hover:border-orange-500/70 transition flex flex-col gap-4 min-h-[320px] break-words">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ $gym->city }}</p>
                            <h2 class="text-2xl font-bold mt-2">{{ $gym->name }}</h2>
                            <p class="text-gray-400 text-sm mt-2 line-clamp-3 break-words">{{ $gym->description }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-950 border border-slate-800">{{ $gym->type }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-sm text-gray-400">
                        <div>
                            <p class="text-gray-500 uppercase tracking-wide text-xs">{{ __('text.Coaches') }}</p>
                            <p class="text-white text-xl font-semibold">{{ $gym->coaches_count }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 uppercase tracking-wide text-xs">{{ __('text.Contact') }}</p>
                            <p class="break-all">{{ $gym->phone ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 uppercase tracking-wide text-xs">{{ __('text.Email') }}</p>
                            <p class="break-all">{{ $gym->email ?? '—' }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16 bg-slate-900/50 border border-dashed border-slate-700 rounded-3xl">
                    <p class="text-gray-400">{{ __('text.No gyms match your filters yet.') }}</p>
                </div>
            @endforelse
        </div>

        <div>
            {{ $gyms->links() }}
        </div>
    </section>
@endsection
