<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    public function index(Request $request)
    {
        $query = Movement::query();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->input('difficulty'));
        }

        if ($request->filled('equipment')) {
            $query->where('equipment', 'like', '%' . $request->input('equipment') . '%');
        }

        $movements = $query->orderBy('name')->paginate(12)->withQueryString();

        $categories = Movement::select('category')->distinct()->orderBy('category')->pluck('category');
        $difficulties = Movement::select('difficulty')->distinct()->orderBy('difficulty')->pluck('difficulty');
        $equipmentOptions = Movement::select('equipment')->whereNotNull('equipment')->distinct()->orderBy('equipment')->pluck('equipment');

        return view('movements.index', [
            'movements' => $movements,
            'categories' => $categories,
            'difficulties' => $difficulties,
            'equipmentOptions' => $equipmentOptions,
            'filters' => $request->only(['category', 'difficulty', 'equipment']),
        ]);
    }

    public function show(Movement $movement)
    {
        $movement->load('wods');

        return view('movements.show', compact('movement'));
    }
}
