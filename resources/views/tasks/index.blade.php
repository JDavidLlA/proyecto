@extends('layouts.app')

@section('title', 'Tareas del proyecto')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Tareas del proyecto</h1>

        <p class="page-subtitle">
            Proyecto:
            <strong>
                {{ $project->nombre ?? $project->titulo ?? $project->name ?? ('Proyecto #' . $project->id) }}
            </strong>
        </p>

        <div class="actions">
            <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                Volver al proyecto
            </a>

            @can('create', [\App\Models\Task::class, $project])
                <a href="{{ route('projects.tasks.create', $project) }}" class="btn btn-success">
                    Nueva tarea
                </a>
            @endcan
        </div>
    </div>

    <div class="page-card">
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
                    @forelse ($tasks as $task)
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

                        <tr>
                            <td>{{ $task->id }}</td>

                            <td>
                                <strong>{{ $task->titulo ?? 'Sin título' }}</strong>
                            </td>

                            <td>
                                <span class="badge {{ $badgeClass }}">
                                    {{ $estadoTexto }}
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
                    @empty
                        <tr>
                            <td colspan="5">
                                No hay tareas registradas para este proyecto.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $tasks->links() }}
        </div>
    </div>
@endsection