@extends('layouts.app')

@section('title', 'Detalle de tarea')

@section('content')
    @php
        $estado = $task->estado ?? 'sin_estado';

        $badgeClass = match($estado) {
            'pendiente' => 'badge-yellow',
            'en_proceso', 'en proceso', 'proceso' => 'badge-blue',
            'completada', 'completado', 'finalizada', 'finalizado' => 'badge-green',
            'cancelada', 'cancelado' => 'badge-red',
            default => 'badge-blue',
        };

        $estadoTexto = ucfirst(str_replace('_', ' ', $estado));
    @endphp

    <div class="page-card">
        <h1 class="page-title">Detalle de tarea</h1>

        <p class="page-subtitle">
            Información completa de la tarea seleccionada.
        </p>

        <p>
            <strong>Proyecto:</strong>
            {{ $project->nombre ?? ('Proyecto #' . $project->id) }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Título:</strong>
            {{ $task->titulo ?? 'Sin título' }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Descripción:</strong><br>
            {{ $task->descripcion ?: 'Sin descripción' }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Estado:</strong>
            <span class="badge {{ $badgeClass }}">
                {{ $estadoTexto }}
            </span>
        </p>

        <p style="margin-top: 12px;">
            <strong>Fecha límite:</strong>
            {{ $task->fecha_limite ? \Illuminate\Support\Carbon::parse($task->fecha_limite)->format('d/m/Y') : 'Sin fecha' }}
        </p>

        <div class="actions" style="margin-top: 18px;">
            <a href="{{ route('projects.tasks.index', $project) }}" class="btn btn-secondary">
                Volver a tareas
            </a>

            @can('update', $task)
                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="btn btn-warning">
                    Editar tarea
                </a>
            @endcan

            @can('delete', $task)
                <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                      method="POST"
                      data-confirm-delete="true"
                      data-confirm-message="¿Seguro que deseas eliminar esta tarea?">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">
                        Eliminar tarea
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <div class="page-card">
        <h2>Comentarios</h2>

        <p class="page-subtitle">
            Comentarios registrados para esta tarea.
        </p>

        @can('create', [\App\Models\Comment::class, $task])
            <form action="{{ route('projects.tasks.comments.store', [$project, $task]) }}" method="POST" style="margin-bottom: 25px;">
                @csrf

                <div>
                    <label for="cuerpo">Nuevo comentario</label>

                    <textarea name="cuerpo"
                              id="cuerpo"
                              rows="4"
                              required>{{ old('cuerpo') }}</textarea>

                    @error('cuerpo')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Guardar comentario
                </button>
            </form>
        @endcan

        @if ($task->comments->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Comentario</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($task->comments as $comment)
                            <tr>
                                <td>
                                    <strong>
                                        {{ $comment->user->name ?? 'Usuario eliminado' }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $comment->cuerpo }}
                                </td>

                                <td>
                                    {{ $comment->created_at?->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    <div class="actions">
                                        @can('view', $comment)
                                            <a href="{{ route('projects.tasks.comments.show', [$project, $task, $comment]) }}" class="btn btn-primary">
                                                Ver
                                            </a>
                                        @endcan

                                        @can('update', $comment)
                                            <a href="{{ route('projects.tasks.comments.edit', [$project, $task, $comment]) }}" class="btn btn-warning">
                                                Editar
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
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>Esta tarea todavía no tiene comentarios.</p>
        @endif
    </div>
@endsection