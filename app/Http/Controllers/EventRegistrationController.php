<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventRegistrationController extends Controller
{
    public function store(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validateWithBag('eventRegistration', [
            'video' => ['required', 'file', 'mimes:mp4,mov,webm', 'max:102400'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [], [
            'video' => __('text.Video file'),
            'notes' => __('text.Notes'),
        ]);

        $user = $request->user();
        $disk = 'public';
        $videoFile = $data['video'];
        $path = $videoFile->store("event-registrations/{$event->id}", $disk);

        $payload = [
            'video_path' => $path,
            'video_disk' => $disk,
            'video_original_name' => $videoFile->getClientOriginalName(),
            'video_size' => $videoFile->getSize(),
            'notes' => $data['notes'] ?? null,
        ];

        $existingRegistration = $event->registrations()
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        if ($existingRegistration) {
            if ($existingRegistration->video_path) {
                Storage::disk($existingRegistration->video_disk ?: $disk)
                    ->delete($existingRegistration->video_path);
            }

            $existingRegistration->fill($payload)->save();
        } else {
            $event->registrations()->create($payload + [
                'user_id' => $user->id,
            ]);
        }

        return redirect()
            ->route('events.show', $event)
            ->with('event_registration_saved', __('text.Video submitted!'));
    }
}
