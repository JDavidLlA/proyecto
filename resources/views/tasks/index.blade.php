@extends('layouts.app')

@section('title', 'Tareas del proyecto')

@section('content')
    @php
        $estados = $estados ?? [
            'pendiente' => 'Pendiente',
            'en_progreso' => 'En progreso',
            'completada' => 'Completada',
        ];

        $prioridades = $prioridades ?? [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];
    @endphp

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
        <h2 class="page-title" style="font-size: 22px;">
            Buscar y filtrar tareas
        </h2>

        <form action="{{ route('projects.tasks.index', $project) }}" method="GET">
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
                        placeholder="Buscar por título o descripción"
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
                    <a href="{{ route('projects.tasks.index', $project) }}" class="btn btn-secondary">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="page-card">
        @if ($tasks->total() > 0)
            <p class="page-subtitle">
                Mostrando {{ $tasks->firstItem() }} a {{ $tasks->lastItem() }}
                de {{ $tasks->total() }} tareas.
            </p>
        @else
            <p class="page-subtitle">
                No se encontraron tareas con los filtros seleccionados.
            </p>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Asignado a</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Fecha límite</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tasks as $task)
                        @php
                            $estadoActual = $task->estado ?? 'pendiente';

                            $estadoBadgeClass = match ($estadoActual) {
                                'pendiente' => 'badge-yellow',
                                'en_progreso' => 'badge-blue',
                                'completada' => 'badge-green',
                                default => 'badge-blue',
                            };

                            $estadoTexto = $estados[$estadoActual] ?? ucfirst(str_replace('_', ' ', $estadoActual));

                            $prioridadActual = $task->prioridad ?? 'media';

                            $prioridadBadgeClass = match ($prioridadActual) {
                                'baja' => 'badge-green',
                                'media' => 'badge-blue',
                                'alta' => 'badge-yellow',
                                'urgente' => 'badge-red',
                                default => 'badge-blue',
                            };

                            $prioridadTexto = $prioridades[$prioridadActual] ?? ucfirst($prioridadActual);

                            $esPrioridadDestacada = in_array($prioridadActual, ['alta', 'urgente'], true);
                        @endphp

                        <tr @if ($esPrioridadDestacada) style="background: #fff7ed; border-left: 5px solid #f97316;" @endif>
                            <td>{{ $task->id }}</td>

                            <td>
                                <strong>{{ $task->titulo ?? 'Sin título' }}</strong>

                                @if ($prioridadActual === 'urgente')
                                    <div style="margin-top: 4px; color: #dc2626; font-size: 13px; font-weight: bold;">
                                        Atención urgente
                                    </div>
                                @elseif ($prioridadActual === 'alta')
                                    <div style="margin-top: 4px; color: #d97706; font-size: 13px; font-weight: bold;">
                                        Prioridad alta
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if ($task->assignee)
                                    <strong>{{ $task->assignee->name }}</strong>
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                                        {{ $task->assignee->email }}
                                    </div>
                                @else
                                    <span class="badge badge-gray">
                                        Sin asignar
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $estadoBadgeClass }}">
                                    {{ $estadoTexto }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $prioridadBadgeClass }}">
                                    {{ $prioridadTexto }}
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
                    @empty
                        <tr>
                            <td colspan="7">
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