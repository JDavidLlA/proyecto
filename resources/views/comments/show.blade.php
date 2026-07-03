@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 30px auto;">
    <h1>Detalle del comentario</h1>

    <div style="background: white; border: 1px solid #ddd; border-radius: 10px; padding: 20px;">
        <p><strong>Proyecto:</strong> {{ $project->nombre }}</p>
        <p><strong>Tarea:</strong> {{ $task->titulo }}</p>
        <p><strong>Usuario:</strong> {{ $comment->user->name ?? 'Usuario eliminado' }}</p>

        <p>
            <strong>Comentario:</strong><br>
            {{ $comment->cuerpo }}
        </p>

        <p>
            <strong>Fecha:</strong>
            {{ $comment->created_at?->format('d/m/Y H:i') }}
        </p>
    </div>

    <div style="margin-top: 20px; display: flex; gap: 10px;">
        <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
           style="padding: 10px 15px; background: #6b7280; color: white; text-decoration: none; border-radius: 8px;">
            Volver
        </a>

        @can('update', $comment)
            <a href="{{ route('projects.tasks.comments.edit', [$project, $task, $comment]) }}"
               style="padding: 10px 15px; background: #ca8a04; color: white; text-decoration: none; border-radius: 8px;">
                Editar
            </a>
        @endcan
    </div>
</div>
@endsection