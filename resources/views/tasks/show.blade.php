@extends('layouts.app')

@section('title', 'Detalle de tarea')

@section('content')
    @php
        $estados = [
            'pendiente' => 'Pendiente',
            'en_progreso' => 'En progreso',
            'completada' => 'Completada',
        ];

        $prioridades = [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];

        $estado = $task->estado ?? 'pendiente';

        $estadoBadgeClass = match ($estado) {
            'pendiente' => 'badge-yellow',
            'en_progreso' => 'badge-blue',
            'completada' => 'badge-green',
            default => 'badge-blue',
        };

        $estadoTexto = $estados[$estado] ?? ucfirst(str_replace('_', ' ', $estado));

        $prioridad = $task->prioridad ?? 'media';

        $prioridadBadgeClass = match ($prioridad) {
            'baja' => 'badge-green',
            'media' => 'badge-blue',
            'alta' => 'badge-yellow',
            'urgente' => 'badge-red',
            default => 'badge-blue',
        };

        $prioridadTexto = $prioridades[$prioridad] ?? ucfirst($prioridad);

        $esPrioridadDestacada = in_array($prioridad, ['alta', 'urgente'], true);
        $estaCompletada = $estado === 'completada';
    @endphp

    <div class="page-card" @if ($esPrioridadDestacada) style="border-left: 6px solid #f97316; background: #fff7ed;" @endif>
        <h1 class="page-title">Detalle de tarea</h1>

        <p class="page-subtitle">
            Información completa de la tarea seleccionada.
        </p>

        @if ($estaCompletada)
            <p style="color: #15803d; font-weight: bold;">
                Esta tarea ya fue completada.
            </p>
        @elseif ($prioridad === 'urgente')
            <p style="color: #dc2626; font-weight: bold;">
                Esta tarea requiere atención urgente.
            </p>
        @elseif ($prioridad === 'alta')
            <p style="color: #d97706; font-weight: bold;">
                Esta tarea tiene prioridad alta.
            </p>
        @endif

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
            <strong>Asignado a:</strong>

            @if ($task->assignee)
                {{ $task->assignee->name }}

                <span style="color: #6b7280;">
                    — {{ $task->assignee->email }}
                </span>
            @else
                <span class="badge badge-gray">
                    Sin asignar
                </span>
            @endif
        </p>

        <p style="margin-top: 12px;">
            <strong>Estado:</strong>
            <span class="badge {{ $estadoBadgeClass }}">
                {{ $estadoTexto }}
            </span>
        </p>

        <p style="margin-top: 12px;">
            <strong>Prioridad:</strong>
            <span class="badge {{ $prioridadBadgeClass }}">
                {{ $prioridadTexto }}
            </span>
        </p>

        <p style="margin-top: 12px;">
            <strong>Fecha límite:</strong>
            {{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Sin fecha' }}
        </p>

        @if ($estaCompletada)
            <div style="margin-top: 14px; padding: 14px; border-radius: 10px; background: #dcfce7; border-left: 5px solid #16a34a;">
                <p>
                    <strong>Tarea completada por:</strong>

                    @if ($task->completedBy)
                        {{ $task->completedBy->name }}
                        <span style="color: #6b7280;">
                            — {{ $task->completedBy->email }}
                        </span>
                    @else
                        Usuario no registrado
                    @endif
                </p>

                <p style="margin-top: 8px;">
                    <strong>Fecha de completado:</strong>
                    {{ $task->completed_at ? $task->completed_at->format('d/m/Y H:i') : 'Sin fecha registrada' }}
                </p>
            </div>
        @endif

        <p style="margin-top: 12px;">
            <strong>Creada:</strong>
            {{ $task->created_at?->format('d/m/Y H:i') }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Última actualización:</strong>
            {{ $task->updated_at?->format('d/m/Y H:i') }}
        </p>

        <div class="actions" style="margin-top: 18px;">
            <a href="{{ route('projects.tasks.index', $project) }}" class="btn btn-secondary">
                Volver a tareas
            </a>

            @can('complete', $task)
                <form action="{{ route('projects.tasks.complete', [$project, $task]) }}"
                      method="POST"
                      data-confirm-delete="true"
                      data-confirm-message="¿Seguro que deseas marcar esta tarea como completada?">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn btn-success">
                        Completar tarea
                    </button>
                </form>
            @endcan

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