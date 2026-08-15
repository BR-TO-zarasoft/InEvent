<?php

namespace App\Http\Requests;

class UpdateEventRequest extends EventRequest
{

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'location' => ['sometimes', 'string', 'max:255'],
            'lat' => ['sometimes', 'numeric', 'between:-90,90'],
            'lon' => ['sometimes', 'numeric', 'between:-180,180'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', 'in:planned,confirmed,cancelled,finished'],
        ];
    }
}
