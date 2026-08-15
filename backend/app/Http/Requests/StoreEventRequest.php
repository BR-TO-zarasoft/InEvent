<?php

namespace App\Http\Requests;

class StoreEventRequest extends EventRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lon' => ['required', 'numeric', 'between:-180,180'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', 'in:planned,confirmed,cancelled,finished'],
        ];
    }
}
