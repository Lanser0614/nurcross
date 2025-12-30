@extends('layouts.app')

@section('title', $event->title . ' · ' . __('text.CrossFit Events'))

@section('content')
    <section class="bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 border-b border-slate-900/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-500">{{ $event->category->label() }}</p>
                <h1 class="text-4xl sm:text-5xl font-black">{{ $event->title }}</h1>
                <p class="text-gray-300 text-lg">
                    {{ __('text.Starts at') }} {{ $event->start_at?->translatedFormat('d M Y, H:i') }}
                </p>
            </div>
            <div class="flex flex-wrap gap-4 text-sm text-gray-300">
                @if($event->gym?->name)
                    <div class="px-4 py-2 rounded-2xl bg-slate-900/60 border border-slate-800">
                        {{ __('text.Hosted at :name', ['name' => $event->gym->name]) }}
                    </div>
                @elseif($event->city)
                    <div class="px-4 py-2 rounded-2xl bg-slate-900/60 border border-slate-800">
                        {{ $event->city }}
                    </div>
                @endif
                @if($event->registration_url)
                    <a href="{{ $event->registration_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2 rounded-2xl bg-orange-500 text-black font-semibold shadow shadow-orange-500/30 hover:bg-orange-400 transition">
                        {{ __('text.Registration') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.25 4.5H19.5V9.75M9.75 19.5H4.5V14.25M19.5 4.5L12 12M4.5 19.5L12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-8 space-y-4">
                <h2 class="text-2xl font-semibold text-white">{{ __('text.Event overview') }}</h2>
                <p class="text-gray-300 whitespace-pre-line">{{ $event->description ?? __('text.Details coming soon') }}</p>
            </div>
            <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-white">{{ __('text.Schedule & logistics') }}</h3>
                <dl class="space-y-3 text-sm text-gray-300">
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500">{{ __('text.Starts at') }}</dt>
                        <dd class="text-right">{{ $event->start_at?->translatedFormat('d M Y, H:i') ?? '—' }}</dd>
                    </div>
                    @if($event->end_at)
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">{{ __('text.Ends') }}</dt>
                            <dd class="text-right">{{ $event->end_at->translatedFormat('d M Y, H:i') }}</dd>
                        </div>
                    @endif
                    @if($event->city)
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">{{ __('text.City') }}</dt>
                            <dd class="text-right">{{ $event->city }}</dd>
                        </div>
                    @endif
                    @if($event->address)
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">{{ __('text.Address') }}</dt>
                            <dd class="text-right">{{ $event->address }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500">{{ __('text.Video submissions') }}</dt>
                        <dd class="text-right font-semibold">{{ $event->registrations_count }}</dd>
                    </div>
                </dl>
                <a href="{{ route('events.index') }}" class="inline-flex items-center text-sm text-orange-400 hover:text-orange-300 transition">
                    {{ __('text.View all') }}
                </a>
            </div>
        </div>

        @if($event->content_video_url)
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 space-y-4">
                <div class="flex flex-col gap-2">
                    <h2 class="text-2xl font-semibold">{{ __('text.Event highlight video') }}</h2>
                    <p class="text-sm text-gray-400">{{ __('text.Shared by admins for this event.') }}</p>
                </div>
                <div class="rounded-2xl overflow-hidden border border-slate-800">
                    <video class="w-full h-auto" controls preload="metadata">
                        <source src="{{ $event->content_video_url }}">
                        {{ __('text.Your browser does not support embedded videos.') }}
                    </video>
                </div>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-semibold">{{ __('text.Submit your registration video') }}</h2>
                        <p class="text-sm text-gray-400">{{ __('text.Video upload requirements', ['formats' => 'MP4, MOV, WEBM', 'size' => 100]) }}</p>
                    </div>
                    <span class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ __('text.:count entries', ['count' => $event->registrations_count]) }}</span>
                </div>

                @auth
                    @if(session('event_registration_saved'))
                        <p class="text-sm text-emerald-300 bg-emerald-500/10 border border-emerald-500/40 rounded-2xl px-4 py-3">
                            {{ session('event_registration_saved') }}
                        </p>
                    @endif

                    @if($existingRegistration)
                        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 text-sm text-gray-300">
                            <p class="font-semibold">{{ __('text.Submitted on :date', ['date' => $existingRegistration->created_at->translatedFormat('d M Y, H:i')]) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('text.Replacing the file will overwrite your current upload.') }}</p>
                            @if($existingRegistration->video_url)
                                <div class="mt-3 rounded-xl overflow-hidden border border-slate-800">
                                    <video controls class="w-full h-auto" preload="metadata">
                                        <source src="{{ $existingRegistration->video_url }}">
                                        {{ __('text.Your browser does not support embedded videos.') }}
                                    </video>
                                </div>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('events.registrations.store', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-sm">
                        @csrf
                        <div>
                            <label for="video" class="text-gray-400 block mb-2">{{ __('text.Video file') }}</label>
                            <input
                                type="file"
                                id="video"
                                name="video"
                                accept=".mp4,.mov,.webm,video/mp4,video/quicktime,video/webm"
                                class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-500/20 file:text-orange-300 hover:file:bg-orange-500/30"
                                required
                            >
                            @error('video', 'eventRegistration')
                                <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="notes" class="text-gray-400 block mb-2">{{ __('text.Notes') }}</label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="3"
                                class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-3 py-2"
                            >{{ old('notes', $existingRegistration->notes ?? '') }}</textarea>
                            @error('notes', 'eventRegistration')
                                <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="px-5 py-3 rounded-2xl bg-orange-500 text-black font-semibold shadow shadow-orange-500/30 hover:bg-orange-400 transition">
                            {{ __('text.Upload video') }}
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-400">
                        {{ __('text.Sign in to upload your video.') }}
                        <a href="{{ route('login') }}" class="text-orange-400 hover:underline">{{ __('text.Sign in') }}</a>
                    </p>
                @endauth
            </div>
            <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">{{ __('text.Latest athlete submissions') }}</h2>
                        <p class="text-sm text-gray-400">{{ __('text.:count entries', ['count' => $recentRegistrations->count()]) }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    @forelse($recentRegistrations as $registration)
                        <div class="border border-slate-800 rounded-2xl p-4">
                            <div class="flex items-center justify-between text-sm text-gray-300">
                                <p class="font-semibold">{{ $registration->user->name }}</p>
                                <p class="text-gray-500">{{ $registration->created_at->translatedFormat('d M, H:i') }}</p>
                            </div>
                            @if($registration->notes)
                                <p class="text-sm text-gray-400 mt-2">{{ $registration->notes }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-400">{{ __('text.No submissions yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
