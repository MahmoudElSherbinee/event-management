<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResouce;
use App\Http\Traits\CanLoadRelationShips;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function __construct(
        private readonly array $relations = ['user', 'event']
    ) { }
    use CanLoadRelationShips;
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $attendees = $event->attendees()->latest();
        return AttendeeResouce::collection($this->loadRelationships($attendees)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
        ]);
        $attendee = $event->attendees()->create($validatedData);
        return new AttendeeResouce($this->loadRelationships($attendee));

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResouce($this->loadRelationships($attendee));
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
