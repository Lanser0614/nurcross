@extends('layouts.app')

@section('title', __('Movement Library'))

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-10">
        <div class="flex flex-col gap-4">
            <p class="text-sm uppercase tracking-[0.4em] text-gray-500">{{ __('Skill library') }}</p>
            <h1 class="text-3xl font-bold">{{ __('Master every movement') }}</h1>
            <p class="text-gray-400 max-w-3xl">
                {{ __('Search by category, difficulty, or equipment to review standards, technique notes, and demo videos.') }}
            </p>
        </div>

        <form method="GET" class="grid gap-4 md:grid-cols-3 bg-slate-900/60 border border-slate-800 rounded-3xl p-6 text-sm">
            <div>
                <label class="text-gray-400 block mb-2" for="category">{{ __('Category') }}</label>
                <select name="category" id="category" class="w-full rounded-2xl bg-slate-950 border border-slate-800 px-3 py-2">
                    <option value="">{{ __('Any') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ ucfirst($category) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-gray-400 block mb-2" for="difficulty">{{ __('Difficulty') }}</label>
                <select name="difficulty" id="difficulty" class="w-full rounded-2xl bg-slate-950 border border-slate-800 px-3 py-2">
                    <option value="">{{ __('Any') }}</option>
                    @foreach($difficulties as $difficulty)
                        <option value="{{ $difficulty }}" @selected(($filters['difficulty'] ?? '') === $difficulty)>{{ ucfirst($difficulty) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-gray-400 block mb-2" for="equipment">{{ __('Equipment') }}</label>
                <select name="equipment" id="equipment" class="w-full rounded-2xl bg-slate-950 border border-slate-800 px-3 py-2">
                    <option value="">{{ __('Any') }}</option>
                    @foreach($equipmentOptions as $equipment)
                        <option value="{{ $equipment }}" @selected(($filters['equipment'] ?? '') === $equipment)>{{ $equipment }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button class="px-5 py-3 rounded-2xl bg-orange-500 text-black font-semibold shadow shadow-orange-500/30">{{ __('Apply filters') }}</button>
            </div>
        </form>

        <div class="grid gap-4 md:grid-cols-3">
            @forelse($movements as $movement)
                <a href="{{ route('movements.show', $movement) }}" class="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 hover:border-orange-500/60 transition flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-500">{{ $movement->category }}</p>
                        <span class="px-3 py-1 rounded-full text-xs bg-slate-950 border border-slate-800">{{ ucfirst($movement->difficulty) }}</span>
                    </div>
                    <h2 class="text-xl font-semibold">{{ $movement->name }}</h2>
                    <p class="text-sm text-gray-400 line-clamp-3">{{ $movement->description_localized }}</p>
                    <p class="text-xs text-gray-500">{{ __('Equipment: :equipment', ['equipment' => $movement->equipment ?? __('Bodyweight')]) }}</p>
                </a>
            @empty
                <p class="text-gray-400">{{ __('No movements match your filters.') }}</p>
            @endforelse
        </div>

        <div>
            {{ $movements->links() }}
        </div>
    </section>
@endsection
