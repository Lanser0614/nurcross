<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use Illuminate\Http\Request;

class GymController extends Controller
{
    public function index(Request $request)
    {
        $query = Gym::query()->withCount('coaches');

        if ($request->filled('city')) {
            $city = $request->input('city');
            $query->where('city', 'like', "%{$city}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $gyms = $query->orderBy('city')->paginate(9)->withQueryString();
        $cities = Gym::select('city')->distinct()->orderBy('city')->pluck('city');
        $types = ['box', 'functional training'];

        return view('gyms.index', [
            'gyms' => $gyms,
            'cities' => $cities,
            'types' => $types,
            'filters' => $request->only(['city', 'type']),
        ]);
    }

    public function show(Gym $gym)
    {
        $gym->load([
            'coaches',
            'wods' => fn ($query) => $query->with('movements')->latest('published_at')->take(6),
        ]);

        return view('gyms.show', compact('gym'));
    }

    public function map()
    {
        $gyms = Gym::query()
            ->select('id', 'name', 'city', 'address', 'type', 'latitude', 'longitude', 'phone', 'website')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('gyms.map', [
            'gyms' => $gyms,
            'gymsJson' => $gyms->map(fn (Gym $gym) => [
                'id' => $gym->id,
                'name' => $gym->name,
                'city' => $gym->city,
                'address' => $gym->address,
                'type' => $gym->type,
                'latitude' => $gym->latitude,
                'longitude' => $gym->longitude,
                'phone' => $gym->phone,
                'website' => $gym->website,
            ])->values()->toJson(),
        ]);
    }
}
