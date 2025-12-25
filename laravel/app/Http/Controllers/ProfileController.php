<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        abort_if(! $user instanceof User, 403);

        $results = $user->wodResults()
            ->with('wod')
            ->latest('performed_at')
            ->get();

        $recentResults = $results->take(5);

        $stats = [
            'total_wods' => $results->count(),
            'rx_rate' => $results->count() ? round($results->where('is_rx', true)->count() / $results->count() * 100) : 0,
            'best_time_seconds' => $results->filter(fn ($result) => $result->time_in_seconds)->min('time_in_seconds'),
            'heaviest_lift' => $results->max('weight_in_kg'),
        ];

        $personalBests = $results->groupBy('wod_id')
            ->map(fn ($group) => $group->sortBy(function ($result) {
                if ($result->time_in_seconds) {
                    return $result->time_in_seconds;
                }

                return -1 * ($result->total_reps ?? 0);
            })->first())
            ->take(3);

        return view('profile.my-wods', [
            'user' => $user,
            'recentResults' => $recentResults,
            'history' => $results,
            'stats' => $stats,
            'personalBests' => $personalBests,
        ]);
    }
}
