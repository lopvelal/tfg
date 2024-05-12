<?php

namespace App\Http\Requests\Reserva;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class StoreRequest extends FormRequest
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
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i:s',
            'duracion' => 'required|integer|min:1',
            'aula_id' => 'required|integer|exists:aulas,id',
            'user_id' => 'required|integer|exists:users,id',
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
