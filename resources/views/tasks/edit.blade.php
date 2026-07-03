@extends('layouts.app')

@section('title', 'Editar tarea')

@section('content')
    @php
        $estados = $estados ?? [
            'pendiente' => 'Pendiente',
            'en_progreso' => 'En progreso',
            'completada' => 'Completada',
        ];

        $prioridades = $prioridades ?? [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];

        $members = $members ?? collect();
    @endphp

    <div class="page-card">
        <h1 class="page-title">Editar tarea</h1>

        <p class="page-subtitle">
            Modifica los datos de la tarea seleccionada.
        </p>

        <p>
            <strong>Proyecto:</strong>
            {{ $project->nombre ?? $project->titulo ?? $project->name ?? ('Proyecto #' . $project->id) }}
        </p>

        @if ($members->count() === 0)
            <p style="margin-top: 12px; color: #b45309; font-weight: bold;">
                Este proyecto todavía no tiene miembros asignados. Primero asigna usuarios al proyecto para poder asignarles tareas.
            </p>

            @can('update', $project)
                <div class="actions" style="margin-top: 12px;">
                    <a href="{{ route('projects.members.index', $project) }}" class="btn btn-warning">
                        Asignar miembros
                    </a>
                </div>
            @endcan
        @endif
    </div>

    <div class="page-card">
        <form action="{{ route('projects.tasks.update', [$project, $task]) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="titulo">Título</label>

                <input type="text"
                       name="titulo"
                       id="titulo"
                       value="{{ old('titulo', $task->titulo) }}"
                       required>

                @error('titulo')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="descripcion">Descripción</label>

                <textarea name="descripcion"
                          id="descripcion"
                          rows="5">{{ old('descripcion', $task->descripcion) }}</textarea>

                @error('descripcion')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="assignee_id">Asignar a</label>

                <select name="assignee_id" id="assignee_id">
                    <option value="">Sin asignar</option>

                    @foreach ($members as $member)
                        <option value="{{ $member->id }}" @selected(old('assignee_id', $task->assignee_id) == $member->id)>
                            {{ $member->name }} - {{ $member->email }}
                            @if ($member->pivot?->project_role)
                                ({{ ucfirst($member->pivot->project_role) }})
                            @endif
                        </option>
                    @endforeach
                </select>

                @error('assignee_id')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="estado">Estado</label>

                <select name="estado" id="estado" required>
                    @foreach ($estados as $valor => $texto)
                        <option value="{{ $valor }}" @selected(old('estado', $task->estado) === $valor)>
                            {{ $texto }}
                        </option>
                    @endforeach
                </select>

                @error('estado')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="prioridad">Prioridad</label>

                <select name="prioridad" id="prioridad" required>
                    @foreach ($prioridades as $valor => $texto)
                        <option value="{{ $valor }}" @selected(old('prioridad', $task->prioridad ?? 'media') === $valor)>
                            {{ $texto }}
                        </option>
                    @endforeach
                </select>

                @error('prioridad')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="due_date">Fecha límite</label>

                <input type="date"
                       name="due_date"
                       id="due_date"
                       value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">

                @error('due_date')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    Actualizar tarea
                </button>

                <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="btn btn-secondary">
                    Cancelar
                </a>

                <a href="{{ route('projects.tasks.index', $project) }}" class="btn btn-dark">
                    Volver a tareas
                </a>
            </div>
        </form>
    </div>
@endsection