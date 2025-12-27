<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GymCoordinateController extends Controller
{
    public function __invoke(Request $request, int $gym): JsonResponse
    {
        $data = $request->validate([
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $model = Gym::query()->findOrFail($gym);

        $model->fill($data);
        $model->save();

        return response()->json([
            'message' => __('moonshine::ui.saved'),
            'gym' => [
                'id' => $model->getKey(),
                'latitude' => $model->latitude,
                'longitude' => $model->longitude,
            ],
        ]);
    }
}
