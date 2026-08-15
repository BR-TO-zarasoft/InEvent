<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Contracts\Pagination\Paginator;

class EventRepository
{
    public function paginate(int $perPage = 15): Paginator
    {
        return Event::query()
            ->orderBy('start_date')
            ->paginate($perPage);
    }

    public function create(array $attributes): Event
    {
        return Event::query()->create($attributes);
    }

    public function update(Event $event, array $attributes): Event
    {
        $event->update($attributes);

        return $event;
    }

    public function delete(Event $event): bool
    {
        return (bool) $event->delete();
    }
}
