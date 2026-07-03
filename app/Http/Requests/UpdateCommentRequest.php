<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cuerpo' => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'cuerpo.required' => 'El comentario es obligatorio.',
            'cuerpo.max' => 'El comentario no debe superar los 5000 caracteres.',
        ];
    }
}