@extends('layouts.app')

@section('title', __('Workout Library'))

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-10">
        <div class="flex flex-col gap-4">
            <p class="text-sm uppercase tracking-[0.4em] text-gray-500">{{ __('Daily grind') }}</p>
            <h1 class="text-3xl font-bold">{{ __('Explore WODs') }}</h1>
            <p class="text-gray-400 max-w-3xl">{{ __('For Time, AMRAP, EMOM, and strength sessions curated for the Uzbek community.') }}</p>
        </div>

        <form method="GET" class="flex flex-col gap-4 md:flex-row md:items-end bg-slate-900/60 border border-slate-800 rounded-3xl p-6 text-sm">
            <div class="flex-1">
                <label class="text-gray-400 block mb-2" for="type">{{ __('Type') }}</label>
                <select name="type" id="type" class="w-full rounded-2xl bg-slate-950 border border-slate-800 px-3 py-2">
                    <option value="">{{ __('Any') }}</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" @selected(($filters['type'] ?? '') === $type)>{{ \App\Models\Wod::translateType($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="text-gray-400 block mb-2" for="difficulty">{{ __('Difficulty') }}</label>
                <select name="difficulty" id="difficulty" class="w-full rounded-2xl bg-slate-950 border border-slate-800 px-3 py-2">
                    <option value="">{{ __('Any') }}</option>
                    @foreach($difficulties as $difficulty)
                        <option value="{{ $difficulty }}" @selected(($filters['difficulty'] ?? '') === $difficulty)>{{ \App\Models\Wod::translateDifficulty($difficulty) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button class="px-6 py-3 rounded-2xl bg-orange-500 text-black font-semibold shadow shadow-orange-500/30">{{ __('Filter') }}</button>
            </div>
        </form>

        <div class="grid gap-6 md:grid-cols-2">
            @forelse($wods as $wod)
                <a href="{{ route('wods.show', $wod) }}" class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 hover:border-orange-500/60 transition flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-gray-500">{{ $wod->difficulty_translated }}</p>
                            <h2 class="text-2xl font-bold mt-2">{{ $wod->title }}</h2>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full bg-slate-950 border border-slate-800 font-semibold uppercase tracking-[0.3em]">{{ $wod->type_translated }}</span>
                    </div>
                    <p class="text-gray-400 text-sm whitespace-pre-line line-clamp-4">{{ \Illuminate\Support\Str::limit($wod->description_localized, 220) }}</p>
                    <div class="flex flex-wrap gap-2 text-xs text-gray-400">
                        @foreach($wod->movements->take(4) as $movement)
                            <span class="px-2 py-1 rounded-full bg-slate-950 border border-slate-800">{{ $movement->name }}</span>
                        @endforeach
                        @if($wod->is_benchmark)
                            <span class="px-2 py-1 rounded-full bg-orange-500/20 border border-orange-500/30 text-orange-200">{{ __('Benchmark') }}</span>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-gray-400">{{ __('No WODs available.') }}</p>
            @endforelse
        </div>

        <div>
            {{ $wods->links() }}
        </div>
    </section>
@endsection
