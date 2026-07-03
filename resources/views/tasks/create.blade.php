@extends('layouts.app')

@section('title', 'Nueva tarea')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Nueva tarea</h1>

        <p class="page-subtitle">
            Crear una nueva tarea para el proyecto seleccionado.
        </p>

        <p>
            <strong>Proyecto:</strong>
            {{ $project->nombre ?? $project->titulo ?? $project->name ?? ('Proyecto #' . $project->id) }}
        </p>
    </div>

    <div class="page-card">
        <form action="{{ route('projects.tasks.store', $project) }}" method="POST">
            @csrf

            <div>
                <label for="titulo">Título</label>

                <input type="text"
                       name="titulo"
                       id="titulo"
                       value="{{ old('titulo') }}"
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
                          rows="5">{{ old('descripcion') }}</textarea>

                @error('descripcion')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="estado">Estado</label>

                <select name="estado" id="estado" required>
                    <option value="pendiente" {{ old('estado') === 'pendiente' ? 'selected' : '' }}>
                        Pendiente
                    </option>

                    <option value="en_proceso" {{ old('estado') === 'en_proceso' ? 'selected' : '' }}>
                        En proceso
                    </option>

                    <option value="completada" {{ old('estado') === 'completada' ? 'selected' : '' }}>
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
                       value="{{ old('fecha_limite') }}">

                @error('fecha_limite')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    Guardar tarea
                </button>

                <a href="{{ route('projects.tasks.index', $project) }}" class="btn btn-secondary">
                    Cancelar
                </a>

                <a href="{{ route('projects.show', $project) }}" class="btn btn-dark">
                    Volver al proyecto
                </a>
            </div>
        </form>
    </div>
@endsection