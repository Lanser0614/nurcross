@extends('layouts.app')

@section('title', __('text.CrossFit Uzbekistan — Community Hub'))

@section('content')


    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-950 to-black">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 grid gap-10 lg:grid-cols-2 items-center">
            <div>
                <p class="uppercase tracking-[0.4em] text-xs text-gray-400">{{ __('text.Stronger Together') }}</p>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight mt-4">
                    {{ __('text.CrossFit Uzbekistan') }}
                </h1>
                <p class="text-lg text-gray-300 mt-6 max-w-xl">
                    {{ __('text.Discover local boxes, track brutal WODs, and master iconic movements. Built for the athletes pushing the CrossFit scene across Uzbekistan.') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('gyms.index') }}" class="px-6 py-3 rounded-full bg-orange-500 text-black font-semibold shadow-lg shadow-orange-500/30 hover:bg-orange-400 transition">
                        {{ __('text.Find a gym') }}
                    </a>
                    <a href="{{ route('profile.wods') }}" class="px-6 py-3 rounded-full border border-gray-600 font-semibold hover:border-orange-400 hover:text-orange-300 transition">
                        {{ __('text.Log my WOD') }}
                    </a>
                </div>
            </div>
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-2xl shadow-orange-500/10">
                <div class="flex flex-wrap items-center justify-between text-gray-400 text-[0.55rem] sm:text-[0.65rem] lg:text-xs uppercase tracking-[0.2em] sm:tracking-[0.35em] gap-x-4 gap-y-1">
                    <span class="leading-tight text-left shrink">{{ __('text.Today\'s intensity') }}</span>
                    <span class="leading-tight text-right shrink">{{ __('text.RX focus') }}</span>
                </div>
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">{{ __('text.Benchmark highlight') }}</p>
                    <a href="{{ route('wods.show', $wod) }}">
                        <h2 class="text-4xl font-black text-orange-400 mt-4">{{ $wod->title }}</h2>
                    </a>
                    <p class="text-gray-300 mt-3">{{ $wod->description_translations[app()->getLocale()] ?? '' }}</p>
{{--                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">--}}
{{--                        <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 text-center sm:text-left">--}}
{{--                            <p class="text-gray-400">{{ __('text.Time cap') }}</p>--}}
{{--                            <p class="text-2xl font-bold text-white">15:00</p>--}}
{{--                        </div>--}}
{{--                        <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 text-center sm:text-left">--}}
{{--                            <p class="text-gray-400">{{ __('text.Difficulty') }}</p>--}}
{{--                            <p class="text-2xl font-bold text-white wrap-break-word">{{ __('text.Advanced') }}</p>--}}
{{--                        </div>--}}

{{--                        <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 text-center sm:text-left">--}}
{{--                            <p class="text-gray-400">{{ __('text.Logged today') }}</p>--}}
{{--                            <p class="text-2xl font-bold text-white">127</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                </div>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-16">
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold">{{ __('text.Popular gyms') }}</h3>
                <a href="{{ route('gyms.index') }}" class="text-sm text-orange-400 hover:underline">{{ __('text.View all') }}</a>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($popularGyms as $gym)
                    <a href="{{ route('gyms.show', $gym) }}" class="bg-slate-900/70 border border-slate-800 rounded-2xl p-5 hover:border-orange-500/70 transition flex flex-col gap-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase text-gray-400">{{ $gym->city }}</p>
                                <h4 class="text-xl font-semibold mt-1">{{ $gym->name }}</h4>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full bg-orange-500/20 text-orange-300 border border-orange-500/40">{{ $gym->type }}</span>
                        </div>
                        <p class="text-gray-400 text-sm line-clamp-3">{{ $gym->description }}</p>
                        <div class="flex gap-6 text-sm text-gray-400">
                            <div>
                                <p class="text-gray-500">{{ __('text.Coaches') }}</p>
                                <p class="text-white font-semibold">{{ $gym->coaches_count }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">{{ __('text.Telephone') }}</p>
                                <p class="text-white font-semibold">{{ $gym->phone ?? '—' }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold">{{ __('text.Featured WODs') }}</h3>
                <a href="{{ route('wods.index') }}" class="text-sm text-orange-400 hover:underline">{{ __('text.View all') }}</a>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($featuredWods as $wod)
                    <a href="{{ route('wods.show', $wod) }}" class="bg-slate-900/70 border border-slate-800 rounded-2xl p-5 hover:border-orange-500/70 transition flex flex-col gap-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xl font-semibold">{{ $wod->title }}</h4>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-gray-200 uppercase tracking-[0.3em]">
                                {{ $wod->type_translated }}
                            </span>
                        </div>
                        <p class="text-gray-400 text-sm whitespace-pre-line">{{ \Illuminate\Support\Str::limit($wod->description_localized, 150) }}</p>
                        <div class="flex flex-wrap gap-2 text-xs text-gray-400">
                            @foreach($wod->movements->take(3) as $movement)
                                <span class="px-2 py-1 rounded-full bg-slate-950 border border-slate-800">{{ $movement->name }}</span>
                            @endforeach
                            @if($wod->movements->count() > 3)
                                <span class="px-2 py-1 rounded-full bg-slate-800 text-orange-300">
                                    {{ __('text.+:count more', ['count' => $wod->movements->count() - 3]) }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold">{{ __('text.New movements added') }}</h3>
                <a href="{{ route('movements.index') }}" class="text-sm text-orange-400 hover:underline">{{ __('text.Browse all') }}</a>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                @foreach($newMovements as $movement)
                    <a href="{{ route('movements.show', $movement) }}" class="bg-slate-900/70 border border-slate-800 rounded-2xl p-5 hover:border-orange-500/70 transition flex flex-col gap-3">
                        <p class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ $movement->category }}</p>
                        <h4 class="text-lg font-semibold">{{ $movement->name }}</h4>
                        <p class="text-sm text-gray-400 line-clamp-2">{{ $movement->description_localized }}</p>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full w-max bg-slate-950 border border-slate-800 text-gray-200">
                            {{ ucfirst($movement->difficulty) }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
