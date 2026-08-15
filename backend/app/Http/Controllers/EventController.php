<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController
{
    public function __construct(private readonly EventRepository $events) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->events->paginate($request->integer('per_page', 15)));
    }

    public function show(Event $event): JsonResponse
    {
        return response()->json($event);
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        return response()->json($this->events->create($request->validated()), 201);
    }

    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        return response()->json($this->events->update($event, $request->validated()));
    }

    public function destroy(Event $event): JsonResponse
    {
        $this->events->delete($event);

        return response()->json(null, 204);
    }
}
