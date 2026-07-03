<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('projects.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', 'in:pendiente,en_proceso,completado'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del proyecto',
            'descripcion' => 'descripción',
            'estado' => 'estado',
        ];
    }
}