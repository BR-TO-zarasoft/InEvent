<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeatherGeocodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function searchQuery(): string
    {
        return (string) $this->validated('q');
    }

    public function limit(): int
    {
        return (int) $this->validated('limit', 5);
    }

    public function rules(): array
    {
        return [
            'q' => ['required', 'string', 'max:255'],
            'limit' => ['sometimes', 'integer', 'between:1,10'],
        ];
    }

    public function messages(): array
    {
        return [
            'q.required' => 'Informe uma cidade para a busca.',
            'q.string' => 'A cidade deve ser um texto.',
            'q.max' => 'A cidade deve ter no máximo 255 caracteres.',
            'limit.integer' => 'O limite deve ser um número inteiro.',
            'limit.between' => 'O limite deve estar entre 1 e 10.',
        ];
    }
}
