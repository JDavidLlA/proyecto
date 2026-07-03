@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 30px auto;">
    <h1>Detalle de tarea</h1>

    @if (session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border: 1px solid #ddd; border-radius: 10px; padding: 20px;">
        <p>
            <strong>Proyecto:</strong>
            {{ $project->nombre ?? $project->titulo ?? $project->name ?? ('Proyecto #' . $project->id) }}
        </p>

        <p><strong>Título:</strong> {{ $task->titulo }}</p>

        <p>
            <strong>Descripción:</strong><br>
            {{ $task->descripcion ?: 'Sin descripción' }}
        </p>

        <p>
            <strong>Estado:</strong>
            {{ str_replace('_', ' ', ucfirst($task->estado)) }}
        </p>

        <p>
            <strong>Fecha límite:</strong>
            {{ $task->fecha_limite ? $task->fecha_limite->format('d/m/Y') : 'Sin fecha' }}
        </p>
    </div>

    <div style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('projects.tasks.index', $project) }}"
           style="padding: 10px 15px; background: #6b7280; color: white; text-decoration: none; border-radius: 8px;">
            Volver a tareas
        </a>

        @can('update', $task)
            <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
               style="padding: 10px 15px; background: #ca8a04; color: white; text-decoration: none; border-radius: 8px;">
                Editar
            </a>
        @endcan

        @can('delete', $task)
            <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                  method="POST"
                  onsubmit="return confirm('¿Seguro que deseas eliminar esta tarea?');">
                @csrf
                @method('DELETE')

                <button type="submit"
                        style="padding: 10px 15px; background: #dc2626; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Eliminar
                </button>
            </form>
        @endcan
    </div>
</div>
@endsection