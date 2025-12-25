@extends('layouts.app')

@section('title', __('Sign in'))

@section('content')
    <div class="max-w-md mx-auto px-4 py-16">
        <div class="bg-slate-900/70 border border-slate-800 rounded-2xl shadow-2xl shadow-orange-500/10 p-8">
            <h1 class="text-2xl font-bold text-orange-400 mb-6">{{ __('Welcome back') }}</h1>
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">{{ __('Email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-xl bg-slate-950/60 border border-slate-800 focus:border-orange-500 focus:ring-orange-500 px-4 py-2.5 text-gray-100">
                    @error('email')
                        <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-300 mb-2">{{ __('Password') }}</label>
                    <input id="password" name="password" type="password" required
                           class="w-full rounded-xl bg-slate-950/60 border border-slate-800 focus:border-orange-500 focus:ring-orange-500 px-4 py-2.5 text-gray-100">
                    @error('password')
                        <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm text-gray-300">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-orange-500 focus:ring-orange-500">
                        <span>{{ __('Remember me') }}</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-400 text-black font-semibold rounded-xl py-3 transition">
                    {{ __('Sign in') }}
                </button>
            </form>

            <p class="mt-6 text-sm text-gray-400 text-center">
                {{ __('Need an account?') }}
                <a href="{{ route('register') }}" class="text-orange-400 font-semibold hover:underline">{{ __('Register now') }}</a>
            </p>
        </div>
    </div>
@endsection
