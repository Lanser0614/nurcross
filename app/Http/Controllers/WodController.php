<?php

namespace App\Http\Controllers;

use App\Models\Wod;
use Illuminate\Http\Request;

class WodController extends Controller
{
    public function index(Request $request)
    {
        $query = Wod::query()->with('movements');

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->input('difficulty'));
        }

        $wods = $query->latest('published_at')->paginate(8)->withQueryString();

        $types = Wod::select('type')->distinct()->orderBy('type')->pluck('type');
        $difficulties = Wod::select('difficulty')->distinct()->orderBy('difficulty')->pluck('difficulty');

        return view('wods.index', [
            'wods' => $wods,
            'types' => $types,
            'difficulties' => $difficulties,
            'filters' => $request->only(['type', 'difficulty']),
        ]);
    }

    public function show(Wod $wod)
    {
        $wod->load([
            'movements',
            'gym',
        ]);

        $recentResults = $wod->results()
            ->with('user')
            ->orderByDesc('performed_at')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return view('wods.show', [
            'wod' => $wod,
            'recentResults' => $recentResults,
        ]);
    }
}
