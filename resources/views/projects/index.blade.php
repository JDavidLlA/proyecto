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
        <h2 class="page-title" style="font-size: 22px;">
            Buscar y filtrar proyectos
        </h2>

        <form action="{{ route('projects.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: 1fr 220px auto auto; gap: 12px; align-items: end;">
                <div>
                    <label for="buscar">
                        Buscar
                    </label>

                    <input
                        type="text"
                        id="buscar"
                        name="buscar"
                        value="{{ request('buscar') }}"
                        placeholder="Buscar por nombre o descripción"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"
                    >
                </div>

                <div>
                    <label for="estado">
                        Estado
                    </label>

                    <select
                        id="estado"
                        name="estado"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"
                    >
                        <option value="">Todos</option>
                        <option value="pendiente" @selected(request('estado') === 'pendiente')>
                            Pendiente
                        </option>
                        <option value="en_proceso" @selected(request('estado') === 'en_proceso')>
                            En proceso
                        </option>
                        <option value="completado" @selected(request('estado') === 'completado')>
                            Completado
                        </option>
                        <option value="cancelado" @selected(request('estado') === 'cancelado')>
                            Cancelado
                        </option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">
                        Filtrar
                    </button>
                </div>

                <div>
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="page-card">
        @if ($projects->total() > 0)
            <p class="page-subtitle">
                Mostrando {{ $projects->firstItem() }} a {{ $projects->lastItem() }}
                de {{ $projects->total() }} proyectos.
            </p>
        @else
            <p class="page-subtitle">
                No se encontraron proyectos con los filtros seleccionados.
            </p>
        @endif

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