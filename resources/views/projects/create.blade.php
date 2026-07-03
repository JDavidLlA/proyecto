@extends('layouts.app')

@section('title', 'Crear proyecto')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Crear proyecto</h1>

        <p class="page-subtitle">
            Completa los datos para registrar un nuevo proyecto en Gestor.
        </p>
    </div>

    <div class="page-card">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div>
                <label for="nombre">Nombre del proyecto</label>

                <input type="text"
                       name="nombre"
                       id="nombre"
                       value="{{ old('nombre') }}"
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
                          rows="4">{{ old('descripcion') }}</textarea>

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
                        <option value="{{ $valor }}" @selected(old('estado', 'activo') === $valor)>
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
                        <option value="{{ $valor }}" @selected(old('prioridad', 'media') === $valor)>
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
                    Guardar proyecto
                </button>

                <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>

                <a href="{{ route('dashboard') }}" class="btn btn-dark">
                    Volver al dashboard
                </a>
            </div>
        </form>
    </div>
@endsection