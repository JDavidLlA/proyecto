@extends('layouts.app')

@section('title', 'Proyectos')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Proyectos</h1>

        <p class="page-subtitle">
            Listado de proyectos registrados en Gestor.
        </p>

        <div class="actions">
            @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="btn btn-success">
                    Crear proyecto
                </a>
            @endcan

            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                Volver al dashboard
            </a>
        </div>
    </div>

    <div class="page-card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Fecha de creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td>{{ $project->id }}</td>

                            <td>
                                <strong>{{ $project->nombre }}</strong>
                            </td>

                            <td>
                                @php
                                    $estado = $project->estado ?? 'sin_estado';

                                    $badgeClass = match($estado) {
                                        'pendiente' => 'badge-yellow',
                                        'en_proceso', 'en proceso', 'proceso' => 'badge-blue',
                                        'completado', 'completada', 'finalizado', 'finalizada' => 'badge-green',
                                        'cancelado', 'cancelada' => 'badge-red',
                                        default => 'badge-blue',
                                    };

                                    $estadoTexto = ucfirst(str_replace('_', ' ', $estado));
                                @endphp

                                <span class="badge {{ $badgeClass }}">
                                    {{ $estadoTexto }}
                                </span>
                            </td>

                            <td>
                                {{ $project->created_at?->format('d/m/Y') }}
                            </td>

                            <td>
                                <div class="actions">
                                    @can('view', $project)
                                        <a href="{{ route('projects.show', $project) }}" class="btn btn-primary">
                                            Ver
                                        </a>
                                    @endcan

                                    @can('viewAny', [\App\Models\Task::class, $project])
                                        <a href="{{ route('projects.tasks.index', $project) }}" class="btn btn-dark">
                                            Tareas
                                        </a>
                                    @endcan

                                    @can('update', $project)
                                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning">
                                            Editar
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
                                No hay proyectos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $projects->links() }}
        </div>
    </div>
@endsection