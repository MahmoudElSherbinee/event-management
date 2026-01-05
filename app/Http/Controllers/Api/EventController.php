<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationShips;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    use CanLoadRelationShips;
    public function __construct(
        private readonly array $relations = ['user', 'attendees', 'attendees.user']
        ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Event::class);
        $event = Event::query();
        $query = $this->loadRelationships($event);
        return EventResource::collection($query->paginate(3));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Event::class);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'

        ]);
        $event = Event::create([...$validatedData, 'user_id' => $request->user()->id]);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        Gate::authorize('view', $event);
        return new EventResource($this->loadRelationships($event, ['user', 'attendees.user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        Gate::authorize('update', $event);
        // if(Gate::denies('update-event', [$event]))
        // {
        //     abort(403, 'da eventak ?');
        // }
        // Gate::authorize('update-event', $event);
        $validatedDate = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time'
        ]);
        $event->update($validatedDate);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        Gate::authorize('delete', $event);
        $event->deleteOrFail();

        return response(status:204);
    }
}
