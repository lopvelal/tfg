<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ChangePassword extends FormRequest
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
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'La contraseña original es requerida',

            'new_password.required' => 'La nueva contraseña es requerida',
            'new_password.min' => 'La nueva contraseña tiene que tener una longitud mínima de 6 caracteres',
            'new_password.confirmed' => 'Las contraseñas no coinciden',

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
