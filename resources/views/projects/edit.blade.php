@extends('layouts.app')

@section('title', 'Editar proyecto')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Editar proyecto</h1>

        <p class="page-subtitle">
            Modifica los datos del proyecto seleccionado.
        </p>

        <p>
            <strong>Proyecto actual:</strong>
            {{ $project->nombre }}
        </p>
    </div>

    <div class="page-card">
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="nombre">Nombre del proyecto</label>

                <input type="text"
                       name="nombre"
                       id="nombre"
                       value="{{ old('nombre', $project->nombre) }}"
                       required>

                @error('nombre')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="descripcion">Descripción</label>

                <textarea name="descripcion"
                          id="descripcion"
                          rows="4">{{ old('descripcion', $project->descripcion) }}</textarea>

                @error('descripcion')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="estado">Estado</label>

                <select name="estado" id="estado" required>
                    @foreach ($estados as $valor => $texto)
                        <option value="{{ $valor }}" @selected(old('estado', $project->estado) === $valor)>
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
                        <option value="{{ $valor }}" @selected(old('prioridad', $project->prioridad ?? 'media') === $valor)>
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

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    Actualizar proyecto
                </button>

                <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                    Cancelar
                </a>

                <a href="{{ route('projects.index') }}" class="btn btn-dark">
                    Volver a proyectos
                </a>
            </div>
        </form>
    </div>
@endsection