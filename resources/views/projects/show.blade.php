@extends('layouts.app')

@section('title', 'Detalle del proyecto')

@section('content')
    @php
        $estadosProyecto = [
            'activo' => 'Activo',
            'pausado' => 'Pausado',
            'finalizado' => 'Finalizado',
        ];

        $estadosTarea = [
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

        $projectEstado = $project->estado ?? 'activo';

        $projectEstadoBadgeClass = match ($projectEstado) {
            'activo' => 'badge-green',
            'pausado' => 'badge-yellow',
            'finalizado' => 'badge-blue',
            default => 'badge-blue',
        };

        $projectEstadoTexto = $estadosProyecto[$projectEstado] ?? ucfirst(str_replace('_', ' ', $projectEstado));

        $projectPrioridad = $project->prioridad ?? 'media';

        $projectPrioridadBadgeClass = match ($projectPrioridad) {
            'baja' => 'badge-green',
            'media' => 'badge-blue',
            'alta' => 'badge-yellow',
            'urgente' => 'badge-red',
            default => 'badge-blue',
        };

        $projectPrioridadTexto = $prioridades[$projectPrioridad] ?? ucfirst($projectPrioridad);

        $projectDestacado = in_array($projectPrioridad, ['alta', 'urgente'], true);
        $proyectoFinalizado = $projectEstado === 'finalizado';
    @endphp

    <div class="page-card" @if ($projectDestacado) style="border-left: 6px solid #f97316; background: #fff7ed;" @endif>
        <h1 class="page-title">{{ $project->nombre }}</h1>

        <p class="page-subtitle">
            Detalle general del proyecto seleccionado.
        </p>

        @if ($proyectoFinalizado)
            <p style="color: #15803d; font-weight: bold;">
                Este proyecto ya fue finalizado.
            </p>
        @elseif ($projectPrioridad === 'urgente')
            <p style="color: #dc2626; font-weight: bold;">
                Este proyecto requiere atención urgente.
            </p>
        @elseif ($projectPrioridad === 'alta')
            <p style="color: #d97706; font-weight: bold;">
                Este proyecto tiene prioridad alta.
            </p>
        @endif

        <p>
            <strong>Estado:</strong>
            <span class="badge {{ $projectEstadoBadgeClass }}">
                {{ $projectEstadoTexto }}
            </span>
        </p>

        <p style="margin-top: 12px;">
            <strong>Prioridad:</strong>
            <span class="badge {{ $projectPrioridadBadgeClass }}">
                {{ $projectPrioridadTexto }}
            </span>
        </p>

        <p style="margin-top: 12px;">
            <strong>Descripción:</strong>
            {{ $project->descripcion ?? 'Sin descripción' }}
        </p>

        @if ($project->owner)
            <p style="margin-top: 12px;">
                <strong>Responsable:</strong>
                {{ $project->owner->name }}

                <span style="color: #6b7280;">
                    — {{ $project->owner->email }}
                </span>
            </p>
        @endif

        @if ($proyectoFinalizado)
            <div style="margin-top: 14px; padding: 14px; border-radius: 10px; background: #dcfce7; border-left: 5px solid #16a34a;">
                <p>
                    <strong>Proyecto finalizado por:</strong>

                    @if ($project->completedBy)
                        {{ $project->completedBy->name }}

                        <span style="color: #6b7280;">
                            — {{ $project->completedBy->email }}
                        </span>
                    @else
                        Usuario no registrado
                    @endif
                </p>

                <p style="margin-top: 8px;">
                    <strong>Fecha de finalización:</strong>
                    {{ $project->completed_at ? $project->completed_at->format('d/m/Y H:i') : 'Sin fecha registrada' }}
                </p>
            </div>
        @endif

        <p style="margin-top: 12px;">
            <strong>Fecha de creación:</strong>
            {{ $project->created_at?->format('d/m/Y H:i') }}
        </p>

        <p style="margin-top: 12px;">
            <strong>Última actualización:</strong>
            {{ $project->updated_at?->format('d/m/Y H:i') }}
        </p>

        <div class="actions" style="margin-top: 18px;">
            @can('complete', $project)
                <form action="{{ route('projects.complete', $project) }}"
                      method="POST"
                      data-confirm-delete="true"
                      data-confirm-message="¿Seguro que deseas finalizar este proyecto?">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn btn-success">
                        Finalizar proyecto
                    </button>
                </form>
            @endcan

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
                            <th>Prioridad</th>
                            <th>Fecha límite</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($project->tasks as $task)
                            @php
                                $taskEstado = $task->estado ?? 'pendiente';

                                $taskEstadoBadgeClass = match ($taskEstado) {
                                    'pendiente' => 'badge-yellow',
                                    'en_progreso' => 'badge-blue',
                                    'completada' => 'badge-green',
                                    default => 'badge-blue',
                                };

                                $taskEstadoTexto = $estadosTarea[$taskEstado] ?? ucfirst(str_replace('_', ' ', $taskEstado));

                                $taskPrioridad = $task->prioridad ?? 'media';

                                $taskPrioridadBadgeClass = match ($taskPrioridad) {
                                    'baja' => 'badge-green',
                                    'media' => 'badge-blue',
                                    'alta' => 'badge-yellow',
                                    'urgente' => 'badge-red',
                                    default => 'badge-blue',
                                };

                                $taskPrioridadTexto = $prioridades[$taskPrioridad] ?? ucfirst($taskPrioridad);

                                $taskDestacada = in_array($taskPrioridad, ['alta', 'urgente'], true);
                            @endphp

                            <tr @if ($taskDestacada) style="background: #fff7ed; border-left: 5px solid #f97316;" @endif>
                                <td>{{ $task->id }}</td>

                                <td>
                                    <strong>{{ $task->titulo ?? 'Sin título' }}</strong>

                                    @if ($taskPrioridad === 'urgente')
                                        <div style="margin-top: 4px; color: #dc2626; font-size: 13px; font-weight: bold;">
                                            Atención urgente
                                        </div>
                                    @elseif ($taskPrioridad === 'alta')
                                        <div style="margin-top: 4px; color: #d97706; font-size: 13px; font-weight: bold;">
                                            Prioridad alta
                                        </div>
                                    @endif

                                    @if ($task->assignee)
                                        <div style="margin-top: 4px; color: #6b7280; font-size: 13px;">
                                            Asignado a: {{ $task->assignee->name }}
                                        </div>
                                    @else
                                        <div style="margin-top: 4px; color: #6b7280; font-size: 13px;">
                                            Sin asignar
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $taskEstadoBadgeClass }}">
                                        {{ $taskEstadoTexto }}
                                    </span>

                                    @if ($taskEstado === 'completada')
                                        <div style="margin-top: 4px; color: #15803d; font-size: 13px; font-weight: bold;">
                                            @if ($task->completedBy)
                                                Completada por: {{ $task->completedBy->name }}
                                            @else
                                                Completada
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $taskPrioridadBadgeClass }}">
                                        {{ $taskPrioridadTexto }}
                                    </span>
                                </td>

                                <td>
                                    {{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Sin fecha' }}
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
                            <th>Rol en proyecto</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($project->members as $member)
                            <tr>
                                <td>
                                    <strong>{{ $member->name }}</strong>
                                </td>

                                <td>{{ $member->email }}</td>

                                <td>
                                    {{ ucfirst($member->pivot->project_role ?? 'miembro') }}
                                </td>
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