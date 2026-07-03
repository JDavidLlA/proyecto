@extends('layouts.app')

@section('title', 'Editar proyecto')

@section('content')
    <div class="card">
        <h1>Editar proyecto</h1>

        <p>Modifica los datos del proyecto seleccionado.</p>
    </div>

    <div class="card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nombre del proyecto</label>
                <input type="text" name="nombre" value="{{ old('nombre', $project->nombre) }}" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <input type="text" name="descripcion" value="{{ old('descripcion', $project->descripcion) }}">
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado" required>
                    <option value="pendiente" @selected(old('estado', $project->estado) === 'pendiente')>
                        Pendiente
                    </option>

                    <option value="en_proceso" @selected(old('estado', $project->estado) === 'en_proceso')>
                        En proceso
                    </option>

                    <option value="completado" @selected(old('estado', $project->estado) === 'completado')>
                        Completado
                    </option>
                </select>
            </div>

            <button type="submit">Actualizar proyecto</button>

            <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                Cancelar
            </a>
        </form>
    </div>
@endsection