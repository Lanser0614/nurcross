@extends('layouts.app')

@section('title', __('text.Create account'))

@section('content')
    <div class="max-w-md mx-auto px-4 py-16">
        <div class="bg-slate-900/70 border border-slate-800 rounded-2xl shadow-2xl shadow-orange-500/10 p-8">
            <h1 class="text-2xl font-bold text-orange-400 mb-6">{{ __('text.Create your account') }}</h1>
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">{{ __('text.Name') }}</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                           class="w-full rounded-xl bg-slate-950/60 border border-slate-800 focus:border-orange-500 focus:ring-orange-500 px-4 py-2.5 text-gray-100">
                    @error('name')
                        <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">{{ __('text.Email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                           class="w-full rounded-xl bg-slate-950/60 border border-slate-800 focus:border-orange-500 focus:ring-orange-500 px-4 py-2.5 text-gray-100">
                    @error('email')
                        <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-300 mb-2">{{ __('text.Password') }}</label>
                    <input id="password" name="password" type="password" required
                           class="w-full rounded-xl bg-slate-950/60 border border-slate-800 focus:border-orange-500 focus:ring-orange-500 px-4 py-2.5 text-gray-100">
                    @error('password')
                        <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-300 mb-2">{{ __('text.Confirm password') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="w-full rounded-xl bg-slate-950/60 border border-slate-800 focus:border-orange-500 focus:ring-orange-500 px-4 py-2.5 text-gray-100">
                </div>

                <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-400 text-black font-semibold rounded-xl py-3 transition">
                    {{ __('text.Join the community') }}
                </button>
            </form>

            <p class="mt-6 text-sm text-gray-400 text-center">
                {{ __('text.Already have an account?') }}
                <a href="{{ route('login') }}" class="text-orange-400 font-semibold hover:underline">{{ __('text.Sign in') }}</a>
            </p>
        </div>
    </div>
@endsection
