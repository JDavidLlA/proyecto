<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        $project = $this->route('project');

        return $project && $this->user()?->can('update', $project);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', Rule::in(['activo', 'pausado', 'finalizado'])],
            'prioridad' => ['required', Rule::in(Project::PRIORIDADES)],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del proyecto',
            'descripcion' => 'descripción',
            'estado' => 'estado',
            'prioridad' => 'prioridad',
        ];
    }

    public function messages(): array
    {
        return [
            'prioridad.required' => 'Debes seleccionar una prioridad.',
            'prioridad.in' => 'La prioridad seleccionada no es válida.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }
}