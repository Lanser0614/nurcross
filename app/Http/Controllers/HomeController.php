<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\Movement;
use App\Models\Wod;

class HomeController extends Controller
{
    public function index()
    {
        $popularGyms = Gym::query()->withCount('coaches')
            ->latest()
            ->take(4)
            ->get();

        $featuredWods = Wod::query()->with('movements')
            ->latest('published_at')
            ->take(4)
            ->get();

        $wod = Wod::query()->where('is_wod_of_day', '=', true)->first();

        $newMovements = Movement::query()->latest()->take(6)->get();

        return view('home', compact('popularGyms', 'featuredWods', 'newMovements', 'wod'));
    }
}
