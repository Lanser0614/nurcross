<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $available = config('app.available_locales', ['en']);

        $validated = $request->validate([
            'locale' => ['required', 'in:'.implode(',', $available)],
        ]);

        session(['locale' => $validated['locale']]);

        return back();
    }
}
