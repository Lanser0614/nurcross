@extends('layouts.app')

@section('title', __('My WODs'))

@section('content')
    @php
        $formatTime = fn (?int $seconds) => $seconds !== null
            ? sprintf('%02d:%02d', intdiv($seconds, 60), $seconds % 60)
            : '—';
    @endphp

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        <div class="flex flex-col gap-2">
            <p class="text-sm uppercase tracking-[0.4em] text-gray-500">{{ __('Athlete dashboard') }}</p>
            <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
            <p class="text-gray-400">{{ __('Overview of recent WODs, RX history, and personal bests.') }}</p>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ __('Total WODs') }}</p>
                <p class="text-4xl font-black mt-4">{{ $stats['total_wods'] }}</p>
            </div>
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ __('RX Rate') }}</p>
                <p class="text-4xl font-black mt-4 text-orange-400">{{ $stats['rx_rate'] }}%</p>
            </div>
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ __('Best time') }}</p>
                <p class="text-4xl font-black mt-4">{{ $formatTime($stats['best_time_seconds'] ?? null) }}</p>
            </div>
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ __('Heaviest lift') }}</p>
                <p class="text-4xl font-black mt-4">{{ $stats['heaviest_lift'] ? $stats['heaviest_lift'].'kg' : '—' }}</p>
            </div>
        </div>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">{{ __('Recent WODs') }}</h2>
                    <span class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ __(':count entries', ['count' => $recentResults->count()]) }}</span>
                </div>
                <div class="space-y-4">
                    @forelse($recentResults as $result)
                        <div class="border border-slate-800 rounded-2xl p-4">
                            <div class="flex items-center justify-between text-sm">
                                <h3 class="font-semibold">{{ $result->wod->title }}</h3>
                                <p class="text-gray-500">{{ optional($result->performed_at)->format('M d') }}</p>
                            </div>
                            <div class="mt-3 grid grid-cols-3 gap-3 text-sm">
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
                                    <p class="line-clamp-2 text-gray-300">{{ $result->notes ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400">{{ __('No logged workouts yet.') }}</p>
                    @endforelse
                </div>
            </div>
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Personal bests') }}</h2>
                <div class="space-y-4">
                    @forelse($personalBests as $best)
                        <div class="border border-slate-800 rounded-2xl p-4">
                            <h3 class="text-lg font-semibold">{{ $best->wod->title }}</h3>
                            <p class="text-sm text-gray-500">{{ optional($best->performed_at)->format('M d, Y') }}</p>
                            <div class="mt-3 grid grid-cols-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('Score') }}</p>
                                    <p class="text-xl font-bold">{{ $best->score_display ?? $formatTime($best->time_in_seconds) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('RX') }}</p>
                                    <p class="text-xl font-bold">{{ $best->is_rx ? 'RX' : strtoupper($best->result_scale) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('Weight') }}</p>
                                    <p class="text-xl font-bold">{{ $best->weight_in_kg ? $best->weight_in_kg.'kg' : 'Body' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400">{{ __('Log workouts to see your PRs.') }}</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                <h2 class="text-xl font-semibold">{{ __('History') }}</h2>
                <p class="text-sm text-gray-500">{{ __('Full log of your submissions') }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-sm">
                    <thead class="bg-slate-900/80 text-gray-400 uppercase tracking-wide text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">{{ __('Date') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('WOD') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('Type') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('Score') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('RX?') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('Notes') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-900">
                        @forelse($history as $result)
                            <tr class="hover:bg-slate-900/40">
                                <td class="px-4 py-3 text-gray-300">{{ optional($result->performed_at)->format('M d, Y') }}</td>
                                <td class="px-4 py-3 font-semibold text-white">{{ $result->wod->title }}</td>
                                <td class="px-4 py-3 text-gray-400 uppercase tracking-[0.3em]">{{ $result->wod->type_translated }}</td>
                                <td class="px-4 py-3 text-gray-200">{{ $result->score_display ?? $formatTime($result->time_in_seconds) }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $result->is_rx ? 'bg-orange-500/30 border border-orange-500/40 text-orange-200' : 'bg-slate-800 border border-slate-700 text-gray-200' }}">
                                        {{ $result->is_rx ? 'RX' : strtoupper($result->result_scale) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-400">{{ $result->notes ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-400">{{ __('No history yet.') }}</td>
                            </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection
