<?php

namespace App\Http\Requests;

class WeatherForecastRequest extends WeatherRequest
{
    public function days(): int
    {
        return (int) $this->validated('days', 10);
    }

    public function rules(): array
    {
        return [
            ...$this->coordinateRules(),
            'days' => ['sometimes', 'integer', 'between:1,10'],
        ];
    }

    public function messages(): array
    {
        return [
            ...$this->coordinateMessages(),
            'days.integer' => 'O número de dias deve ser um inteiro.',
            'days.between' => 'O número de dias deve estar entre 1 e 10.',
        ];
    }
}
