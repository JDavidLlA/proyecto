@extends('layouts.app')

@section('title', 'Crear proyecto')

@section('content')
    <div class="card">
        <h1>Crear proyecto</h1>

        <p>Completa los datos para registrar un nuevo proyecto.</p>
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

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nombre del proyecto</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <input type="text" name="descripcion" value="{{ old('descripcion') }}">
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado" required>
                    <option value="pendiente" @selected(old('estado') === 'pendiente')>
                        Pendiente
                    </option>

                    <option value="en_proceso" @selected(old('estado') === 'en_proceso')>
                        En proceso
                    </option>

                    <option value="completado" @selected(old('estado') === 'completado')>
                        Completado
                    </option>
                </select>
            </div>

            <button type="submit">Guardar proyecto</button>

            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </form>
    </div>
@endsection