<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResouce;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $attendees = $event->attendees()->latest();
        return AttendeeResouce::collection($attendees->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        // if($event->id != $request['event_id'])
        // {
        //     return response()->json([
        //         'message' => 'the event id is incorrect'
        //     ]);
        // }
        $validatedData = $request->validate([
            'user_id' => 'required',
        ]);
        $attendee = $event->attendees()->create($validatedData);
        return new AttendeeResouce($attendee);

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResouce($attendee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event, Attendee $attendee)
    {
        $validatedData = $request->validate([
            'user_id' => 'sometimes',
        ]);
        $attendee = $event->attendees()->update($validatedData);
        return new AttendeeResouce($attendee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        $attendee->deleteOrFail();
        return response(status:204);
    }
}
