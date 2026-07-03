@extends('layouts.app')

@section('title', 'Editar comentario')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Editar comentario</h1>

        <p class="page-subtitle">
            Modifica el comentario seleccionado.
        </p>

        <p>
            <strong>Proyecto:</strong>
            {{ $project->nombre ?? 'Proyecto sin nombre' }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Tarea:</strong>
            {{ $task->titulo ?? 'Tarea sin título' }}
        </p>
    </div>

    <div class="page-card">
        <form action="{{ route('projects.tasks.comments.update', [$project, $task, $comment]) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="cuerpo">Comentario</label>

                <textarea name="cuerpo"
                          id="cuerpo"
                          rows="6"
                          required>{{ old('cuerpo', $comment->cuerpo) }}</textarea>

                @error('cuerpo')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    Actualizar comentario
                </button>

                <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="btn btn-secondary">
                    Cancelar
                </a>

                @can('view', $comment)
                    <a href="{{ route('projects.tasks.comments.show', [$project, $task, $comment]) }}" class="btn btn-dark">
                        Ver comentario
                    </a>
                @endcan
            </div>
        </form>
    </div>
@endsection