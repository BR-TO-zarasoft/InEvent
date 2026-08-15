<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do evento é obrigatório.',
            'name.string' => 'O nome do evento deve ser um texto.',
            'name.max' => 'O nome do evento deve ter no máximo 255 caracteres.',
            'location.required' => 'O local do evento é obrigatório.',
            'location.string' => 'O local do evento deve ser um texto.',
            'location.max' => 'O local do evento deve ter no máximo 255 caracteres.',
            'lat.required' => 'A latitude é obrigatória.',
            'lat.numeric' => 'A latitude deve ser um número.',
            'lat.between' => 'A latitude deve estar entre -90 e 90.',
            'lon.required' => 'A longitude é obrigatória.',
            'lon.numeric' => 'A longitude deve ser um número.',
            'lon.between' => 'A longitude deve estar entre -180 e 180.',
            'start_date.required' => 'A data de início é obrigatória.',
            'start_date.date' => 'A data de início deve ser uma data válida.',
            'end_date.required' => 'A data de término é obrigatória.',
            'end_date.date' => 'A data de término deve ser uma data válida.',
            'end_date.after_or_equal' => 'A data de término deve ser igual ou posterior à data de início.',
            'description.string' => 'A descrição deve ser um texto.',
            'status.string' => 'O status deve ser um texto.',
            'status.in' => 'O status deve ser um destes valores: planned, confirmed, cancelled ou finished.',
        ];
    }
}
