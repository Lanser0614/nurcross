<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $available = config('app.available_locales', ['en']);
        $locale = session('locale', config('app.locale'));

        if (! in_array($locale, $available, true)) {
            $locale = config('app.fallback_locale', 'ru');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
