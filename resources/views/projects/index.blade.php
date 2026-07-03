@extends('layouts.app')

@section('title', 'Proyectos')

@section('content')
    <div class="card">
        <h1>Proyectos</h1>

        <p>Listado de proyectos registrados en Gestor.</p>

        @can('create', App\Models\Project::class)
            <a href="{{ route('projects.create') }}" class="btn">
                Crear proyecto
            </a>
        @endcan
    </div>

    <div class="card">
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
                        <td>{{ $project->nombre }}</td>
                        <td>
                            <span class="badge">
                                {{ str_replace('_', ' ', ucfirst($project->estado)) }}
                            </span>
                        </td>
                        <td>{{ $project->created_at?->format('d/m/Y') }}</td>
                        <td>
                            @can('view', $project)
                                <a href="{{ route('projects.show', $project) }}" class="btn">
                                    Ver
                                </a>
                            @endcan

                            @can('viewAny', [\App\Models\Task::class, $project])
                                <a href="{{ route('projects.tasks.index', $project) }}" class="btn">
                                    Tareas
                                </a>
                            @endcan

                            @can('update', $project)
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary">
                                    Editar
                                </a>
                            @endcan

                            @can('delete', $project)
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-danger"
                                        onclick="return confirm('¿Seguro que deseas eliminar este proyecto?')">
                                        Eliminar
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay proyectos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $projects->links() }}
        </div>
    </div>
@endsection