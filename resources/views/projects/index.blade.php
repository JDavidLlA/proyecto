@extends('layouts.app')

@section('title', 'Proyectos')

@section('content')
    @php
        $estados = $estados ?? [
            'activo' => 'Activo',
            'pausado' => 'Pausado',
            'finalizado' => 'Finalizado',
        ];

        $prioridades = $prioridades ?? [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];
    @endphp

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
            <div style="display: grid; grid-template-columns: 1fr 200px 200px auto auto; gap: 12px; align-items: end;">
                <div>
                    <label for="buscar">
                        Buscar
                    </label>

                    <input
                        type="text"
                        id="buscar"
                        name="buscar"
                        value="{{ $buscar ?? request('buscar') }}"
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

                        @foreach ($estados as $valor => $texto)
                            <option value="{{ $valor }}" @selected(($estado ?? request('estado')) === $valor)>
                                {{ $texto }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="prioridad">
                        Prioridad
                    </label>

                    <select
                        id="prioridad"
                        name="prioridad"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"
                    >
                        <option value="">Todas</option>

                        @foreach ($prioridades as $valor => $texto)
                            <option value="{{ $valor }}" @selected(($prioridad ?? request('prioridad')) === $valor)>
                                {{ $texto }}
                            </option>
                        @endforeach
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
                        <th>Prioridad</th>
                        <th>Fecha de creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($projects as $project)
                        @php
                            $estadoActual = $project->estado ?? 'activo';

                            $estadoBadgeClass = match ($estadoActual) {
                                'activo' => 'badge-green',
                                'pausado' => 'badge-yellow',
                                'finalizado' => 'badge-blue',
                                default => 'badge-blue',
                            };

                            $estadoTexto = $estados[$estadoActual] ?? ucfirst(str_replace('_', ' ', $estadoActual));

                            $prioridadActual = $project->prioridad ?? 'media';

                            $prioridadBadgeClass = match ($prioridadActual) {
                                'baja' => 'badge-green',
                                'media' => 'badge-blue',
                                'alta' => 'badge-yellow',
                                'urgente' => 'badge-red',
                                default => 'badge-blue',
                            };

                            $prioridadTexto = $prioridades[$prioridadActual] ?? ucfirst($prioridadActual);

                            $esPrioridadDestacada = in_array($prioridadActual, ['alta', 'urgente'], true);

                            $proyectoFinalizado = $estadoActual === 'finalizado';
                        @endphp

                        <tr @if ($esPrioridadDestacada) style="background: #fff7ed; border-left: 5px solid #f97316;" @endif>
                            <td>{{ $project->id }}</td>

                            <td>
                                <strong>{{ $project->nombre }}</strong>

                                @if ($prioridadActual === 'urgente')
                                    <div style="margin-top: 4px; color: #dc2626; font-size: 13px; font-weight: bold;">
                                        Atención urgente
                                    </div>
                                @elseif ($prioridadActual === 'alta')
                                    <div style="margin-top: 4px; color: #d97706; font-size: 13px; font-weight: bold;">
                                        Prioridad alta
                                    </div>
                                @endif

                                @if ($proyectoFinalizado)
                                    <div style="margin-top: 8px; color: #15803d; font-size: 13px; font-weight: bold;">
                                        Proyecto finalizado
                                    </div>
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $estadoBadgeClass }}">
                                    {{ $estadoTexto }}
                                </span>

                                @if ($proyectoFinalizado)
                                    <div style="margin-top: 8px; font-size: 13px; color: #15803d;">
                                        <strong>Finalizado por:</strong>

                                        @if ($project->completedBy)
                                            {{ $project->completedBy->name }}
                                        @else
                                            Usuario no registrado
                                        @endif
                                    </div>

                                    <div style="margin-top: 4px; font-size: 12px; color: #6b7280;">
                                        <strong>Fecha:</strong>
                                        {{ $project->completed_at ? $project->completed_at->format('d/m/Y H:i') : 'Sin fecha registrada' }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $prioridadBadgeClass }}">
                                    {{ $prioridadTexto }}
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
                                        <a href="{{ route('projects.members.index', $project) }}" class="btn btn-success">
                                            Miembros
                                        </a>

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
                            <td colspan="6">
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