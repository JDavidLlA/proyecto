@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 30px auto;">
    <h1>Editar tarea</h1>

    <p>
        <strong>Proyecto:</strong>
        {{ $project->nombre ?? $project->titulo ?? $project->name ?? ('Proyecto #' . $project->id) }}
    </p>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
            <strong>Corrige los siguientes errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projects.tasks.update', [$project, $task]) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="titulo">Título</label>
            <input type="text"
                   name="titulo"
                   id="titulo"
                   value="{{ old('titulo', $task->titulo) }}"
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;"
                   required>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion"
                      id="descripcion"
                      rows="5"
                      style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">{{ old('descripcion', $task->descripcion) }}</textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="estado">Estado</label>
            <select name="estado"
                    id="estado"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;"
                    required>
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
        </div>

        <div style="margin-bottom: 15px;">
            <label for="fecha_limite">Fecha límite</label>
            <input type="date"
                   name="fecha_limite"
                   id="fecha_limite"
                   value="{{ old('fecha_limite', $task->fecha_limite ? $task->fecha_limite->format('Y-m-d') : '') }}"
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit"
                    style="padding: 10px 15px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer;">
                Actualizar tarea
            </button>

            <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
               style="padding: 10px 15px; background: #6b7280; color: white; text-decoration: none; border-radius: 8px;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection