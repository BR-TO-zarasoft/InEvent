<?php

namespace App\Http\Requests;

class WeatherCurrentRequest extends WeatherRequest
{
    public function rules(): array
    {
        return $this->coordinateRules();
    }

    public function messages(): array
    {
        return $this->coordinateMessages();
    }
}
