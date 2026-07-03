@extends('layouts.app')

@section('title', 'Detalle del proyecto')

@section('content')
    <div class="card">
        <h1>{{ $project->nombre }}</h1>

        <p>
            <strong>Estado:</strong>
            <span class="badge">
                {{ str_replace('_', ' ', ucfirst($project->estado)) }}
            </span>
        </p>

        <p>
            <strong>Descripción:</strong>
            {{ $project->descripcion ?? 'Sin descripción' }}
        </p>

        <p>
            <strong>Fecha de creación:</strong>
            {{ $project->created_at?->format('d/m/Y H:i') }}
        </p>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
            @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}" class="btn">
                    Editar proyecto
                </a>
            @endcan

            @can('viewAny', [\App\Models\Task::class, $project])
                <a href="{{ route('projects.tasks.index', $project) }}" class="btn">
                    Ver tareas
                </a>
            @endcan

            @can('create', [\App\Models\Task::class, $project])
                <a href="{{ route('projects.tasks.create', $project) }}" class="btn">
                    Nueva tarea
                </a>
            @endcan

            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>
    </div>

    <div class="card">
        <h2>Tareas del proyecto</h2>

        @can('create', [\App\Models\Task::class, $project])
            <a href="{{ route('projects.tasks.create', $project) }}" class="btn">
                Agregar tarea
            </a>
        @endcan

        @if ($project->tasks->count() > 0)
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
                        <tr>
                            <td>{{ $task->id }}</td>

                            <td>
                                {{ $task->titulo ?? 'Sin título' }}
                            </td>

                            <td>
                                {{ str_replace('_', ' ', ucfirst($task->estado ?? 'Sin estado')) }}
                            </td>

                            <td>
                                {{ $task->fecha_limite ? $task->fecha_limite->format('d/m/Y') : 'Sin fecha' }}
                            </td>

                            <td>
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    @can('view', $task)
                                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="btn">
                                            Ver
                                        </a>
                                    @endcan

                                    @can('update', $task)
                                        <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="btn">
                                            Editar
                                        </a>
                                    @endcan

                                    @can('delete', $task)
                                        <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar esta tarea?');">
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
        @else
            <p>Este proyecto todavía no tiene tareas registradas.</p>
        @endif
    </div>

    <div class="card">
        <h2>Miembros del proyecto</h2>

        @if ($project->relationLoaded('members') && $project->members->count() > 0)
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
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Este proyecto todavía no tiene miembros registrados.</p>
        @endif
    </div>
@endsection