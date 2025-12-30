<?php

namespace App\Http\Controllers;

use App\Enums\EventCategory;
use App\Models\Event;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $categoryValues = array_map(static fn (EventCategory $case) => $case->value, EventCategory::cases());
        $gymOptions = Gym::query()
            ->orderBy('name')
            ->pluck('name', 'id');

        $query = Event::query()
            ->with('gym')
            ->orderBy('start_at');

        if ($request->filled('category') && in_array($request->input('category'), $categoryValues, true)) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('gym') && $gymOptions->keys()->contains((int) $request->input('gym'))) {
            $query->where('gym_id', $request->input('gym'));
        }

        if (! $request->boolean('include_past', false)) {
            $query->where('start_at', '>=', now()->subDay());
        }

        $events = $query->paginate(9)->withQueryString();

        return view('events.index', [
            'events' => $events,
            'categories' => EventCategory::options(),
            'gyms' => $gymOptions,
            'filters' => $request->only(['category', 'gym', 'include_past']),
        ]);
    }

    public function show(Request $request, Event $event): View
    {
        $event->load('gym')->loadCount('registrations');

        $recentRegistrations = $event->registrations()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $existingRegistration = null;

        if ($request->user()) {
            $existingRegistration = $event->registrations()
                ->where('user_id', $request->user()->id)
                ->latest()
                ->first();
        }

        return view('events.show', [
            'event' => $event,
            'recentRegistrations' => $recentRegistrations,
            'existingRegistration' => $existingRegistration,
        ]);
    }
}
