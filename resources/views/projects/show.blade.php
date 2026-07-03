@extends('layouts.app')

@section('title', 'Detalle del proyecto')

@section('content')
    @php
        $projectEstado = $project->estado ?? 'sin_estado';

        $projectBadgeClass = match($projectEstado) {
            'pendiente' => 'badge-yellow',
            'en_proceso', 'en proceso', 'proceso' => 'badge-blue',
            'completado', 'completada', 'finalizado', 'finalizada' => 'badge-green',
            'cancelado', 'cancelada' => 'badge-red',
            default => 'badge-blue',
        };

        $projectEstadoTexto = ucfirst(str_replace('_', ' ', $projectEstado));
    @endphp

    <div class="page-card">
        <h1 class="page-title">{{ $project->nombre }}</h1>

        <p class="page-subtitle">
            Detalle general del proyecto seleccionado.
        </p>

        <p>
            <strong>Estado:</strong>
            <span class="badge {{ $projectBadgeClass }}">
                {{ $projectEstadoTexto }}
            </span>
        </p>

        <p style="margin-top: 12px;">
            <strong>Descripción:</strong>
            {{ $project->descripcion ?? 'Sin descripción' }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Fecha de creación:</strong>
            {{ $project->created_at?->format('d/m/Y H:i') }}
        </p>

        <div class="actions" style="margin-top: 18px;">
            @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning">
                    Editar proyecto
                </a>
            @endcan

            @can('viewAny', [\App\Models\Task::class, $project])
                <a href="{{ route('projects.tasks.index', $project) }}" class="btn btn-primary">
                    Ver tareas
                </a>
            @endcan

            @can('create', [\App\Models\Task::class, $project])
                <a href="{{ route('projects.tasks.create', $project) }}" class="btn btn-success">
                    Nueva tarea
                </a>
            @endcan

            @can('delete', $project)
                <form action="{{ route('projects.destroy', $project) }}"
                      method="POST"
                      data-confirm-delete="true"
                      data-confirm-message="¿Seguro que deseas eliminar este proyecto? También puede afectar sus tareas y comentarios.">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">
                        Eliminar proyecto
                    </button>
                </form>
            @endcan

            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>
    </div>

    <div class="page-card">
        <div class="actions" style="justify-content: space-between; margin-bottom: 15px;">
            <div>
                <h2>Tareas del proyecto</h2>
                <p class="page-subtitle" style="margin-bottom: 0;">
                    Tareas relacionadas directamente con este proyecto.
                </p>
            </div>

            @can('create', [\App\Models\Task::class, $project])
                <a href="{{ route('projects.tasks.create', $project) }}" class="btn btn-success">
                    Agregar tarea
                </a>
            @endcan
        </div>

        @if ($project->tasks->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Estado</th>
                            <th>Fecha límite</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($project->tasks as $task)
                            @php
                                $taskEstado = $task->estado ?? 'sin_estado';

                                $taskBadgeClass = match($taskEstado) {
                                    'pendiente' => 'badge-yellow',
                                    'en_proceso', 'en proceso', 'proceso' => 'badge-blue',
                                    'completada', 'completado', 'finalizada', 'finalizado' => 'badge-green',
                                    'cancelada', 'cancelado' => 'badge-red',
                                    default => 'badge-blue',
                                };

                                $taskEstadoTexto = ucfirst(str_replace('_', ' ', $taskEstado));
                            @endphp

                            <tr>
                                <td>{{ $task->id }}</td>

                                <td>
                                    <strong>{{ $task->titulo ?? 'Sin título' }}</strong>
                                </td>

                                <td>
                                    <span class="badge {{ $taskBadgeClass }}">
                                        {{ $taskEstadoTexto }}
                                    </span>
                                </td>

                                <td>
                                    {{ $task->fecha_limite ? \Illuminate\Support\Carbon::parse($task->fecha_limite)->format('d/m/Y') : 'Sin fecha' }}
                                </td>

                                <td>
                                    <div class="actions">
                                        @can('view', $task)
                                            <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="btn btn-primary">
                                                Ver
                                            </a>
                                        @endcan

                                        @can('update', $task)
                                            <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="btn btn-warning">
                                                Editar
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
            <p>Este proyecto todavía no tiene tareas registradas.</p>
        @endif
    </div>

    <div class="page-card">
        <h2>Miembros del proyecto</h2>

        <p class="page-subtitle">
            Usuarios relacionados con este proyecto.
        </p>

        @if ($project->relationLoaded('members') && $project->members->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($project->members as $member)
                            <tr>
                                <td>
                                    <strong>{{ $member->name }}</strong>
                                </td>

                                <td>{{ $member->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>Este proyecto todavía no tiene miembros registrados.</p>
        @endif
    </div>
@endsection