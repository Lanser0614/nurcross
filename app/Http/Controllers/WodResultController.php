<?php

namespace App\Http\Controllers;

use App\Models\Wod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WodResultController extends Controller
{
    public function store(Request $request, Wod $wod): RedirectResponse
    {
        $validator = validator($request->all(), [
            'time_in_seconds' => ['nullable', 'integer', 'min:0'],
            'total_reps' => ['nullable', 'integer', 'min:0'],
            'weight_in_kg' => ['nullable', 'numeric', 'min:0'],
            'result_scale' => ['required', 'in:rx,scaled,modified'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [], [
            'time_in_seconds' => __('text.Time (seconds)'),
            'total_reps' => __('text.Total reps'),
            'weight_in_kg' => __('text.Weight (kg)'),
        ]);

        $validator->after(function ($validator) use ($request) {
            if (
                ! $request->filled('time_in_seconds') &&
                ! $request->filled('total_reps') &&
                ! $request->filled('weight_in_kg')
            ) {
                $validator->errors()->add(
                    'time_in_seconds',
                    __('text.Enter at least one metric (time, reps, or weight).')
                );
            }
        });

        $data = $validator->validateWithBag('wodResult');

        $wod->results()->create([
            'user_id' => $request->user()->id,
            'gym_id' => $wod->gym_id,
            'time_in_seconds' => $data['time_in_seconds'] ?? null,
            'total_reps' => $data['total_reps'] ?? null,
            'weight_in_kg' => $data['weight_in_kg'] ?? null,
            'is_rx' => ($data['result_scale'] ?? 'rx') === 'rx',
            'result_scale' => $data['result_scale'],
            'notes' => $data['notes'] ?? null,
            'score_display' => $this->formatScoreDisplay($data),
            'performed_at' => now(),
        ]);

        return redirect()
            ->route('wods.show', $wod)
            ->with('wod_result_saved', __('text.Result saved!'));
    }

    private function formatScoreDisplay(array $data): ?string
    {
        if (! empty($data['time_in_seconds'])) {
            $seconds = (int) $data['time_in_seconds'];

            $hours = intdiv($seconds, 3600);
            $minutes = intdiv($seconds % 3600, 60);
            $remainingSeconds = $seconds % 60;

            if ($hours > 0) {
                return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
            }

            return sprintf('%02d:%02d', $minutes, $remainingSeconds);
        }

        if (! empty($data['total_reps'])) {
            return $data['total_reps'] . ' reps';
        }

        if (! empty($data['weight_in_kg'])) {
            $weight = rtrim(rtrim(number_format($data['weight_in_kg'], 2, '.', ''), '0'), '.');

            return $weight . ' kg';
        }

        return null;
    }
}
