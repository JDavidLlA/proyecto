<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $task = $this->route('task');

        return $task && $this->user()?->can('update', $task);
    }

    public function rules(): array
    {
        $project = $this->route('project');
        $task = $this->route('task');

        if (! $project && $task && $task->project) {
            $project = $task->project;
        }

        $assigneeRule = Rule::exists('users', 'id');

        if ($project) {
            $assigneeRule = Rule::exists('project_user', 'user_id')
                ->where(function ($query) use ($project) {
                    $query->where('project_id', $project->id);
                });
        }

        return [
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', Rule::in(['pendiente', 'en_progreso', 'completada'])],
            'prioridad' => ['required', Rule::in(Task::PRIORIDADES)],
            'assignee_id' => ['nullable', $assigneeRule],
            'due_date' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'titulo' => 'título de la tarea',
            'descripcion' => 'descripción',
            'estado' => 'estado',
            'prioridad' => 'prioridad',
            'assignee_id' => 'usuario asignado',
            'due_date' => 'fecha límite',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título de la tarea es obligatorio.',
            'titulo.max' => 'El título no debe superar los 255 caracteres.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',

            'prioridad.required' => 'Debes seleccionar una prioridad.',
            'prioridad.in' => 'La prioridad seleccionada no es válida.',

            'assignee_id.exists' => 'El usuario asignado debe pertenecer al proyecto.',

            'due_date.date' => 'La fecha límite debe ser una fecha válida.',
        ];
    }
}