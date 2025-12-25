<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('CrossFit Uzbekistan'))</title>
    <meta name="description" content="{{ __('CrossFit Uzbekistan community platform for gyms, WODs, coaches, and movement library.') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-950 text-gray-100 min-h-screen antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="border-b border-slate-800 bg-slate-900/70 backdrop-blur">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <a href="{{ route('home') }}" class="text-xl font-bold uppercase tracking-wide text-orange-400">
                    {{ __('CrossFit Uzbekistan') }}
                </a>
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-6 text-sm font-semibold">
                    <nav class="flex items-center gap-4 text-base">
                        <a href="{{ route('gyms.index') }}" class="hover:text-orange-400 transition">{{ __('Gyms') }}</a>
                        <a href="{{ route('gyms.map') }}" class="hover:text-orange-400 transition">{{ __('Gyms map') }}</a>
                        <a href="{{ route('movements.index') }}" class="hover:text-orange-400 transition">{{ __('Movements') }}</a>
                        <a href="{{ route('wods.index') }}" class="hover:text-orange-400 transition">{{ __('WODs') }}</a>
                    </nav>
                    <div class="flex items-center gap-3 flex-wrap">
                        @auth
                            <a href="{{ route('profile.wods') }}" class="px-3 py-1 rounded-full border border-orange-500 text-orange-400 hover:bg-orange-500 hover:text-black transition">
                                {{ __('My WODs') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs uppercase tracking-wide text-gray-400 hover:text-orange-400 transition">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-xs uppercase tracking-wide text-gray-400 hover:text-orange-400 transition">
                                {{ __('Sign in') }}
                            </a>
                            <a href="{{ route('register') }}" class="px-3 py-1 rounded-full border border-orange-500 text-orange-400 hover:bg-orange-500 hover:text-black transition">
                                {{ __('Join now') }}
                            </a>
                        @endauth
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
                <p>&copy; {{ date('Y') }} {{ __('CrossFit Uzbekistan Community') }}</p>
                <p>{{ __('Built with Laravel & Tailwind · Stay savage.') }}</p>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
