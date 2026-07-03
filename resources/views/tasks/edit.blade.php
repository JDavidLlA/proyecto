@extends('layouts.app')

@section('title', 'Editar tarea')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Editar tarea</h1>

        <p class="page-subtitle">
            Modifica los datos de la tarea seleccionada.
        </p>

        <p>
            <strong>Proyecto:</strong>
            {{ $project->nombre ?? $project->titulo ?? $project->name ?? ('Proyecto #' . $project->id) }}
        </p>
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
                <label for="estado">Estado</label>

                <select name="estado" id="estado" required>
                    <option value="pendiente" @selected(old('estado', $task->estado) === 'pendiente')>
                        Pendiente
                    </option>

                    <option value="en_proceso" @selected(old('estado', $task->estado) === 'en_proceso')>
                        En proceso
                    </option>

                    <option value="completada" @selected(old('estado', $task->estado) === 'completada')>
                        Completada
                    </option>
                </select>

                @error('estado')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="fecha_limite">Fecha límite</label>

                <input type="date"
                       name="fecha_limite"
                       id="fecha_limite"
                       value="{{ old('fecha_limite', $task->fecha_limite ? \Illuminate\Support\Carbon::parse($task->fecha_limite)->format('Y-m-d') : '') }}">

                @error('fecha_limite')
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