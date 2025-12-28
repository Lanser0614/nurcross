<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('text.CrossFit Uzbekistan'))</title>
    <meta name="description" content="{{ __('text.CrossFit Uzbekistan community platform for gyms, WODs, coaches, and movement library.') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-950 text-gray-100 min-h-screen antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="border-b border-slate-800 bg-slate-900/70 backdrop-blur">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('home') }}" class="text-xl font-bold uppercase tracking-wide text-orange-400">
                        {{ __('text.CrossFit Uzbekistan') }}
                    </a>
                    <button type="button"
                            class="inline-flex items-center justify-center rounded-md border border-slate-700 p-2 text-gray-200 hover:text-orange-400 hover:border-orange-500 transition md:hidden"
                            data-mobile-menu-toggle
                            aria-controls="site-mobile-menu"
                            aria-expanded="false">
                        <span class="sr-only">{{ __('text.Toggle navigation') }}</span>
                        <svg data-open-icon class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg data-close-icon class="hidden h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="site-mobile-menu"
                     data-mobile-menu
                     class="mt-4 hidden flex-col gap-4 border-t border-slate-800 pt-4 text-sm font-semibold md:mt-0 md:flex md:flex-row md:items-center md:justify-between md:border-none md:pt-0">
                    <nav class="flex flex-col gap-3 text-base md:flex-row md:items-center md:gap-6">
                        <a href="{{ route('gyms.index') }}" class="hover:text-orange-400 transition">{{ __('text.Gyms') }}</a>
                        <a href="{{ route('gyms.map') }}" class="hover:text-orange-400 transition">{{ __('text.Gyms map') }}</a>
                        <a href="{{ route('movements.index') }}" class="hover:text-orange-400 transition">{{ __('text.Movements') }}</a>
                        <a href="{{ route('events.index') }}" class="hover:text-orange-400 transition">{{ __('text.Events') }}</a>
                        <a href="{{ route('wods.index') }}" class="hover:text-orange-400 transition">{{ __('text.WODs') }}</a>
                    </nav>
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            @auth
                                <a href="{{ route('profile.wods') }}" class="px-3 py-1 rounded-full border border-orange-500 text-orange-400 hover:bg-orange-500 hover:text-black transition">
                                    {{ __('text.My WODs') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-xs uppercase tracking-wide text-gray-400 hover:text-orange-400 transition">
                                        {{ __('text.Logout') }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-xs uppercase tracking-wide text-gray-400 hover:text-orange-400 transition">
                                    {{ __('text.Sign in') }}
                                </a>
                                <a href="{{ route('register') }}" class="px-3 py-1 rounded-full border border-orange-500 text-orange-400 hover:bg-orange-500 hover:text-black transition">
                                    {{ __('text.Join now') }}
                                </a>
                            @endauth
                        </div>
                        <form method="POST" action="{{ route('locale.update') }}" class="text-xs font-semibold">
                            @csrf
                            <select name="locale" class="bg-slate-900 border border-slate-700 rounded-full px-3 py-1 text-gray-300" onchange="this.form.submit()">
                                @foreach(config('app.available_locales', ['en']) as $locale)
                                    <option value="{{ $locale }}" @selected(app()->getLocale() === $locale)>{{ strtoupper($locale) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="border-t border-slate-800 bg-slate-900/80 mt-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-400 flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between">
                <p>&copy; {{ date('Y') }} {{ __('text.CrossFit Uzbekistan Community') }}</p>
                <p>{{ __('text.Built with Laravel & Tailwind · Stay savage.') }}</p>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
