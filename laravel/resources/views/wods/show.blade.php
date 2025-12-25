@extends('layouts.app')

@section('title', $wod->title . ' · ' . __('Workout'))

@section('content')
    @php
        $formatTime = fn (?int $seconds) => $seconds
            ? sprintf('%02d:%02d', intdiv($seconds, 60), $seconds % 60)
            : '—';
    @endphp

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-8 space-y-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-500">{{ $wod->difficulty_translated }}</p>
                    <h1 class="text-4xl font-bold mt-2">{{ $wod->title }}</h1>
                    <p class="text-gray-400 text-sm mt-2">{{ $wod->gym?->name }}</p>
                </div>
                <div class="flex flex-wrap gap-3 text-sm text-gray-300">
                    <span class="px-3 py-1 rounded-full bg-slate-950 border border-slate-800 uppercase tracking-[0.3em]">{{ $wod->type_translated }}</span>
                    @if($wod->is_benchmark)
                        <span class="px-3 py-1 rounded-full bg-orange-500/20 border border-orange-500/30 text-orange-200">{{ __('Benchmark') }}</span>
                    @endif
                    @if($wod->time_cap_seconds)
                        <span class="px-3 py-1 rounded-full bg-slate-950 border border-slate-800">{{ __('Cap: :time', ['time' => $formatTime($wod->time_cap_seconds)]) }}</span>
                    @endif
                </div>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ __('Workout flow') }}</p>
                    <p class="text-gray-200 mt-3 whitespace-pre-line">{{ $wod->description_localized }}</p>
                </div>
                <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ __('Strategy notes') }}</p>
                    <p class="text-gray-300 mt-3">{{ $wod->strategy_notes_localized ?? __('Keep breathing steady, smooth transitions, no missed reps.') }}</p>
                </div>
            </div>
        </div>

        <section>
            <h2 class="text-2xl font-bold mb-4">{{ __('Movements') }}</h2>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach($wod->movements as $movement)
                    <a href="{{ route('movements.show', $movement) }}" class="bg-slate-900/50 border border-slate-800 rounded-3xl p-5 hover:border-orange-500/50 transition flex gap-4">
                        <div class="text-3xl font-black text-orange-400">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ $movement->category }}</p>
                            <h3 class="text-xl font-semibold mt-1">{{ $movement->name }}</h3>
                            <p class="text-sm text-gray-400 mt-2">{{ __('Scheme: :scheme', ['scheme' => $movement->pivot_rep_scheme_localized ?? __('Varies')]) }}</p>
                            <p class="text-sm text-gray-400">{{ __('Load: :load', ['load' => $movement->pivot_load_localized ?? __('Bodyweight')]) }}</p>
                            @if($movement->pivot_notes_localized)
                                <p class="text-xs text-gray-500 mt-2">{{ $movement->pivot_notes_localized }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="grid gap-8 lg:grid-cols-2">
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Log your result') }}</h2>
                @auth
                    @if(session('wod_result_saved'))
                        <p class="mb-4 text-sm text-emerald-300 bg-emerald-500/10 border border-emerald-500/40 rounded-2xl px-4 py-3">
                            {{ session('wod_result_saved') }}
                        </p>
                    @endif

                    <form action="{{ route('wods.results.store', $wod) }}" method="POST" class="grid gap-4 text-sm">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-gray-400 block mb-1" for="time_in_seconds">{{ __('Time (seconds)') }}</label>
                                <input
                                    id="time_in_seconds"
                                    name="time_in_seconds"
                                    type="number"
                                    min="0"
                                    class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-3 py-2"
                                    placeholder="{{ __('e.g. 785') }}"
                                    value="{{ old('time_in_seconds') }}"
                                >
                                @error('time_in_seconds', 'wodResult')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-gray-400 block mb-1" for="total_reps">{{ __('Total reps') }}</label>
                                <input
                                    id="total_reps"
                                    name="total_reps"
                                    type="number"
                                    min="0"
                                    class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-3 py-2"
                                    value="{{ old('total_reps') }}"
                                >
                                @error('total_reps', 'wodResult')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-gray-400 block mb-1" for="weight_in_kg">{{ __('Weight (kg)') }}</label>
                                <input
                                    id="weight_in_kg"
                                    name="weight_in_kg"
                                    type="number"
                                    step="0.5"
                                    min="0"
                                    class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-3 py-2"
                                    value="{{ old('weight_in_kg') }}"
                                >
                                @error('weight_in_kg', 'wodResult')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-gray-400 block mb-1" for="result_scale">{{ __('RX/Scaled') }}</label>
                                <select
                                    id="result_scale"
                                    name="result_scale"
                                    class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-3 py-2 uppercase tracking-[0.2em]"
                                >
                                    @foreach(['rx' => 'RX', 'scaled' => 'Scaled', 'modified' => 'Modified'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('result_scale', 'rx') === $value)>{{ strtoupper($label) }}</option>
                                    @endforeach
                                </select>
                                @error('result_scale', 'wodResult')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="text-gray-400 block mb-1" for="notes">{{ __('Notes') }}</label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="3"
                                class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-3 py-2"
                                placeholder="{{ __('Felt heavy, broke thrusters 12/9...') }}">{{ old('notes') }}</textarea>
                            @error('notes', 'wodResult')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="mt-2 px-5 py-3 rounded-2xl bg-orange-500 text-black font-semibold shadow shadow-orange-500/30 hover:bg-orange-400 transition" type="submit">
                            {{ __('Save result') }}
                        </button>
                        <p class="text-xs text-gray-500">{{ __('Your submission will appear in the latest results below.') }}</p>
                    </form>
                @else
                    <p class="text-sm text-gray-400">
                        {{ __('Please sign in to log your result.') }}
                        <a href="{{ route('login') }}" class="text-orange-400 hover:underline">{{ __('Sign in') }}</a>
                    </p>
                @endauth
            </div>
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">{{ __('Latest results') }}</h2>
                    <span class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ __(':count entries', ['count' => $recentResults->count()]) }}</span>
                </div>
                <div class="space-y-4">
                    @forelse($recentResults as $result)
                        <div class="border border-slate-800 rounded-2xl p-4">
                            <div class="flex items-center justify-between text-sm">
                                <p class="font-semibold">{{ $result->user->name }}</p>
                                <p class="text-gray-500">{{ optional($result->performed_at)->format('M d, H:i') }}</p>
                            </div>
                            <div class="mt-2 grid grid-cols-3 text-sm text-gray-300 gap-2">
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('Score') }}</p>
                                    <p class="text-lg font-semibold">{{ $result->score_display ?? $formatTime($result->time_in_seconds) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('RX') }}</p>
                                    <p class="text-lg font-semibold">{{ $result->is_rx ? 'RX' : strtoupper($result->result_scale) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('Notes') }}</p>
                                    <p class="line-clamp-2">{{ $result->notes ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400">{{ __('No results logged yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </section>
    </section>
@endsection
