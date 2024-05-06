<?php

namespace App\Http\Requests\Reserva;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => 'required|min:4',
            'fecha' => 'required|date|after:yesterday',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'descripcion' => 'nullable|min:4',
        ];
    }


    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es requerido.',
            'titulo.min' => 'La longitud mínima del título debe ser de 4 caracteres.',

            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'Debe proporcionar una fecha válida.',
            'fecha.after' => 'La fecha debe ser posterior al día de ayer.',

            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format' => 'La hora de inicio debe estar en formato de 24 horas (HH:mm).',

            'hora_fin.required' => 'La hora de fin es obligatoria.',
            'hora_fin.date_format' => 'La hora de fin debe estar en formato de 24 horas (HH:mm).',

            'descripcion.min' => 'La descripción debe tener al menos 4 caracteres si se proporciona.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $response = new Response($validator->errors(), 422);
            throw new ValidationException($validator, $response);
        }
    }
}
