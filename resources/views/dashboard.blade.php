@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .dashboard-header {
        margin-bottom: 25px;
    }

    .dashboard-header h1 {
        font-size: 32px;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .dashboard-header p {
        color: #6b7280;
        font-size: 15px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 22px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-left: 6px solid #2563eb;
    }

    .stat-card.purple {
        border-left-color: #7c3aed;
    }

    .stat-card.yellow {
        border-left-color: #f59e0b;
    }

    .stat-card.orange {
        border-left-color: #f97316;
    }

    .stat-card.green {
        border-left-color: #16a34a;
    }

    .stat-card.indigo {
        border-left-color: #4f46e5;
    }

    .stat-card.red {
        border-left-color: #dc2626;
    }

    .stat-card p {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .stat-card h2 {
        font-size: 34px;
        font-weight: bold;
        color: #111827;
    }

    .quick-actions {
        background: #ffffff;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .quick-actions h2,
    .section-card h2 {
        font-size: 22px;
        color: #1f2937;
        margin-bottom: 18px;
        font-weight: bold;
    }

    .buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .btn {
        display: inline-block;
        padding: 10px 16px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: 0.2s;
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .btn-blue {
        background: #2563eb;
    }

    .btn-green {
        background: #16a34a;
    }

    .btn-dark {
        background: #374151;
    }

    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
        gap: 24px;
    }

    .section-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 22px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .section-card.priority-alert {
        border-left: 6px solid #f97316;
        background: #fff7ed;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 10px;
    }

    .section-header a {
        font-size: 14px;
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }

    .section-header a:hover {
        text-decoration: underline;
    }

    .list-item {
        padding: 15px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .list-item:last-child {
        border-bottom: none;
    }

    .list-item.priority-high {
        padding: 15px;
        margin-bottom: 10px;
        border-bottom: none;
        border-left: 5px solid #f59e0b;
        background: #fffbeb;
        border-radius: 10px;
    }

    .list-item.priority-urgent {
        padding: 15px;
        margin-bottom: 10px;
        border-bottom: none;
        border-left: 5px solid #dc2626;
        background: #fef2f2;
        border-radius: 10px;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
    }

    .item-title {
        color: #111827;
        font-weight: bold;
        font-size: 16px;
        text-decoration: none;
    }

    .item-title:hover {
        color: #2563eb;
    }

    .item-description {
        color: #6b7280;
        font-size: 14px;
        margin-top: 5px;
        line-height: 1.4;
    }

    .item-date {
        color: #9ca3af;
        font-size: 12px;
        margin-top: 6px;
    }

    .badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-yellow {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-orange {
        background: #ffedd5;
        color: #c2410c;
    }

    .badge-green {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-red {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-gray {
        background: #f3f4f6;
        color: #4b5563;
    }

    .empty-text {
        color: #6b7280;
        font-size: 15px;
    }

    .task-meta {
        text-align: right;
        min-width: 120px;
    }

    .task-comments {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 8px;
    }

    @media (max-width: 600px) {
        .dashboard-container {
            padding: 20px 12px;
        }

        .dashboard-header h1 {
            font-size: 26px;
        }

        .content-grid {
            grid-template-columns: 1fr;
        }

        .item-row {
            flex-direction: column;
        }

        .task-meta {
            text-align: left;
        }
    }
</style>

<div class="dashboard-container">

    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Resumen general del sistema Gestor.</p>
    </div>

    <div class="stats-grid">

        @can('projects.view')
            <div class="stat-card">
                <p>Total de proyectos</p>
                <h2>{{ $totalProjects ?? 0 }}</h2>
            </div>
        @endcan

        @can('tasks.view')
            <div class="stat-card purple">
                <p>Total de tareas</p>
                <h2>{{ $totalTasks ?? 0 }}</h2>
            </div>

            <div class="stat-card yellow">
                <p>Tareas pendientes</p>
                <h2>{{ $pendingTasks ?? 0 }}</h2>
            </div>

            <div class="stat-card orange">
                <p>Tareas en progreso</p>
                <h2>{{ $inProcessTasks ?? 0 }}</h2>
            </div>

            <div class="stat-card green">
                <p>Tareas completadas</p>
                <h2>{{ $completedTasks ?? 0 }}</h2>
            </div>

            <div class="stat-card yellow">
                <p>Tareas prioridad alta</p>
                <h2>{{ $highPriorityTasksCount ?? 0 }}</h2>
            </div>

            <div class="stat-card red">
                <p>Tareas urgentes</p>
                <h2>{{ $urgentPriorityTasksCount ?? 0 }}</h2>
            </div>
        @endcan

        @can('comments.view')
            <div class="stat-card indigo">
                <p>Total de comentarios</p>
                <h2>{{ $totalComments ?? 0 }}</h2>
            </div>
        @endcan

    </div>

    <div class="quick-actions">
        <h2>Botones rápidos</h2>

        <div class="buttons">
            @can('projects.view')
                <a href="{{ route('projects.index') }}" class="btn btn-blue">
                    Ver proyectos
                </a>
            @endcan

            @can('projects.create')
                <a href="{{ route('projects.create') }}" class="btn btn-green">
                    Crear proyecto
                </a>
            @endcan

            @can('users.view')
                @if(\Illuminate\Support\Facades\Route::has('admin.users.index'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-dark">
                        Administrar usuarios
                    </a>
                @endif
            @endcan
        </div>
    </div>

    <div class="content-grid">

        @can('projects.view')
            <div class="section-card">
                <div class="section-header">
                    <h2>Últimos proyectos</h2>

                    <a href="{{ route('projects.index') }}">
                        Ver todos
                    </a>
                </div>

                @forelse($latestProjects ?? [] as $project)
                    @php
                        $projectPrioridad = $project->prioridad ?? 'media';

                        $projectPrioridadClass = match ($projectPrioridad) {
                            'baja' => 'badge-green',
                            'media' => 'badge-blue',
                            'alta' => 'badge-yellow',
                            'urgente' => 'badge-red',
                            default => 'badge-gray',
                        };

                        $projectPrioridadTexto = ucfirst($projectPrioridad);
                    @endphp

                    <div class="list-item">
                        <div class="item-row">
                            <div>
                                <a href="{{ route('projects.show', $project->id) }}" class="item-title">
                                    {{ $project->nombre ?? $project->name ?? $project->titulo ?? 'Proyecto sin nombre' }}
                                </a>

                                <p class="item-description">
                                    {{ $project->descripcion ?? $project->description ?? 'Sin descripción' }}
                                </p>

                                <p class="item-date">
                                    Creado: {{ $project->created_at?->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="task-meta">
                                <span class="badge badge-blue">
                                    {{ $project->tasks_count ?? 0 }} tareas
                                </span>

                                <p style="margin-top: 8px;">
                                    <span class="badge {{ $projectPrioridadClass }}">
                                        {{ $projectPrioridadTexto }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="empty-text">
                        No hay proyectos registrados todavía.
                    </p>
                @endforelse
            </div>
        @endcan

        @can('tasks.view')
            <div class="section-card priority-alert">
                <div class="section-header">
                    <h2>Tareas altas o urgentes</h2>

                    @if(isset($firstProjectWithPriorityTasks) && $firstProjectWithPriorityTasks)
                        <a href="{{ route('projects.tasks.index', $firstProjectWithPriorityTasks) }}?prioridad=alta">
                            Ver filtro
                        </a>
                    @endif
                </div>

                @forelse($priorityTasks ?? [] as $task)
                    @php
                        $prioridad = $task->prioridad ?? 'media';

                        $priorityItemClass = match ($prioridad) {
                            'urgente' => 'priority-urgent',
                            'alta' => 'priority-high',
                            default => '',
                        };

                        $priorityBadgeClass = match ($prioridad) {
                            'urgente' => 'badge-red',
                            'alta' => 'badge-yellow',
                            default => 'badge-blue',
                        };

                        $prioridadTexto = ucfirst($prioridad);

                        $estado = $task->estado ?? 'pendiente';

                        $estadoBadgeClass = match ($estado) {
                            'pendiente' => 'badge-yellow',
                            'en_progreso' => 'badge-orange',
                            'completada' => 'badge-green',
                            default => 'badge-gray',
                        };

                        $estadoTexto = ucfirst(str_replace('_', ' ', $estado));
                    @endphp

                    <div class="list-item {{ $priorityItemClass }}">
                        <div class="item-row">
                            <div>
                                <a href="{{ route('projects.tasks.show', [$task->project_id, $task->id]) }}" class="item-title">
                                    {{ $task->titulo ?? $task->title ?? $task->nombre ?? 'Tarea sin título' }}
                                </a>

                                <p class="item-description">
                                    Proyecto:
                                    {{ $task->project->nombre ?? $task->project->name ?? $task->project->titulo ?? 'Sin proyecto' }}
                                </p>

                                <p class="item-date">
                                    Creada: {{ $task->created_at?->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="task-meta">
                                <span class="badge {{ $priorityBadgeClass }}">
                                    {{ $prioridadTexto }}
                                </span>

                                <p style="margin-top: 8px;">
                                    <span class="badge {{ $estadoBadgeClass }}">
                                        {{ $estadoTexto }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="empty-text">
                        No hay tareas con prioridad alta o urgente.
                    </p>
                @endforelse
            </div>
        @endcan

        @can('tasks.view')
            <div class="section-card">
                <h2>Últimas tareas</h2>

                @forelse($latestTasks ?? [] as $task)
                    @php
                        $estado = $task->estado ?? 'pendiente';

                        $estadoBadgeClass = match ($estado) {
                            'pendiente' => 'badge-yellow',
                            'en_progreso' => 'badge-orange',
                            'completada' => 'badge-green',
                            default => 'badge-gray',
                        };

                        $estadoTexto = ucfirst(str_replace('_', ' ', $estado));

                        $prioridad = $task->prioridad ?? 'media';

                        $prioridadBadgeClass = match ($prioridad) {
                            'baja' => 'badge-green',
                            'media' => 'badge-blue',
                            'alta' => 'badge-yellow',
                            'urgente' => 'badge-red',
                            default => 'badge-gray',
                        };

                        $prioridadTexto = ucfirst($prioridad);
                    @endphp

                    <div class="list-item">
                        <div class="item-row">
                            <div>
                                @if(isset($task->project_id))
                                    <a href="{{ route('projects.tasks.show', [$task->project_id, $task->id]) }}" class="item-title">
                                        {{ $task->titulo ?? $task->title ?? $task->nombre ?? 'Tarea sin título' }}
                                    </a>
                                @else
                                    <p class="item-title">
                                        {{ $task->titulo ?? $task->title ?? $task->nombre ?? 'Tarea sin título' }}
                                    </p>
                                @endif

                                <p class="item-description">
                                    Proyecto:
                                    {{ $task->project->nombre ?? $task->project->name ?? $task->project->titulo ?? 'Sin proyecto' }}
                                </p>

                                <p class="item-date">
                                    Creada: {{ $task->created_at?->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="task-meta">
                                <span class="badge {{ $estadoBadgeClass }}">
                                    {{ $estadoTexto }}
                                </span>

                                <p style="margin-top: 8px;">
                                    <span class="badge {{ $prioridadBadgeClass }}">
                                        {{ $prioridadTexto }}
                                    </span>
                                </p>

                                <p class="task-comments">
                                    {{ $task->comments_count ?? 0 }} comentarios
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="empty-text">
                        No hay tareas registradas todavía.
                    </p>
                @endforelse
            </div>
        @endcan

    </div>

</div>
@endsection