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
        min-width: 110px;
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
                <h2>{{ $totalProjects }}</h2>
            </div>
        @endcan

        @can('tasks.view')
            <div class="stat-card purple">
                <p>Total de tareas</p>
                <h2>{{ $totalTasks }}</h2>
            </div>

            <div class="stat-card yellow">
                <p>Tareas pendientes</p>
                <h2>{{ $pendingTasks }}</h2>
            </div>

            <div class="stat-card orange">
                <p>Tareas en proceso</p>
                <h2>{{ $inProcessTasks }}</h2>
            </div>

            <div class="stat-card green">
                <p>Tareas completadas</p>
                <h2>{{ $completedTasks }}</h2>
            </div>
        @endcan

        @can('comments.view')
            <div class="stat-card indigo">
                <p>Total de comentarios</p>
                <h2>{{ $totalComments }}</h2>
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

                @forelse($latestProjects as $project)
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

                            <div>
                                <span class="badge badge-blue">
                                    {{ $project->tasks_count ?? 0 }} tareas
                                </span>
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
            <div class="section-card">
                <h2>Últimas tareas</h2>

                @forelse($latestTasks as $task)
                    @php
                        $estado = $task->estado ?? 'sin_estado';

                        $badgeClass = match($estado) {
                            'pendiente' => 'badge-yellow',
                            'en_proceso', 'en proceso', 'proceso' => 'badge-orange',
                            'completada', 'completado', 'finalizada', 'finalizado' => 'badge-green',
                            default => 'badge-gray',
                        };

                        $estadoTexto = ucfirst(str_replace('_', ' ', $estado));
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
                                <span class="badge {{ $badgeClass }}">
                                    {{ $estadoTexto }}
                                </span>

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