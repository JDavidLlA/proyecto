@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 30px auto;">
    <h1>Editar comentario</h1>

    <p><strong>Proyecto:</strong> {{ $project->nombre }}</p>
    <p><strong>Tarea:</strong> {{ $task->titulo }}</p>

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

    <form action="{{ route('projects.tasks.comments.update', [$project, $task, $comment]) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="cuerpo">Comentario</label>
            <textarea name="cuerpo"
                      id="cuerpo"
                      rows="6"
                      style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;"
                      required>{{ old('cuerpo', $comment->cuerpo) }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit"
                    style="padding: 10px 15px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer;">
                Actualizar comentario
            </button>

            <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
               style="padding: 10px 15px; background: #6b7280; color: white; text-decoration: none; border-radius: 8px;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection