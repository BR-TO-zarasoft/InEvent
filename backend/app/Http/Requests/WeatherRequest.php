<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class WeatherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function latitude(): float
    {
        return (float) $this->validated('lat');
    }

    public function longitude(): float
    {
        return (float) $this->validated('lon');
    }

    public function units(): string
    {
        return (string) ($this->validated('units') ?? config('openweather.units'));
    }

    protected function coordinateRules(): array
    {
        return [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lon' => ['required', 'numeric', 'between:-180,180'],
            'units' => ['sometimes', 'in:metric,imperial,standard'],
        ];
    }

    protected function coordinateMessages(): array
    {
        return [
            'lat.required' => 'A latitude é obrigatória.',
            'lat.numeric' => 'A latitude deve ser um número.',
            'lat.between' => 'A latitude deve estar entre -90 e 90.',
            'lon.required' => 'A longitude é obrigatória.',
            'lon.numeric' => 'A longitude deve ser um número.',
            'lon.between' => 'A longitude deve estar entre -180 e 180.',
            'units.in' => 'A unidade deve ser metric, imperial ou standard.',
        ];
    }
}
