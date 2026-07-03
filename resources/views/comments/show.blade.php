@extends('layouts.app')

@section('title', 'Detalle del comentario')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Detalle del comentario</h1>

        <p class="page-subtitle">
            Información del comentario registrado en la tarea.
        </p>

        <p>
            <strong>Proyecto:</strong>
            {{ $project->nombre ?? 'Proyecto sin nombre' }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Tarea:</strong>
            {{ $task->titulo ?? 'Tarea sin título' }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Usuario:</strong>
            {{ $comment->user->name ?? 'Usuario eliminado' }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Comentario:</strong><br>
            {{ $comment->cuerpo }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Fecha:</strong>
            {{ $comment->created_at?->format('d/m/Y H:i') }}
        </p>

        <div class="actions" style="margin-top: 18px;">
            <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="btn btn-secondary">
                Volver a la tarea
            </a>

            @can('update', $comment)
                <a href="{{ route('projects.tasks.comments.edit', [$project, $task, $comment]) }}" class="btn btn-warning">
                    Editar comentario
                </a>
            @endcan

            @can('delete', $comment)
                <form action="{{ route('projects.tasks.comments.destroy', [$project, $task, $comment]) }}"
                      method="POST"
                      data-confirm-delete="true"
                      data-confirm-message="¿Seguro que deseas eliminar este comentario?">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">
                        Eliminar comentario
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection