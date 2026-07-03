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

        @can('update', $project)
            <a href="{{ route('projects.edit', $project) }}" class="btn">
                Editar proyecto
            </a>
        @endcan

        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

    <div class="card">
        <h2>Tareas del proyecto</h2>

        @if ($project->relationLoaded('tasks') && $project->tasks->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($project->tasks as $task)
                        <tr>
                            <td>{{ $task->id }}</td>
                            <td>{{ $task->titulo ?? $task->nombre ?? 'Sin título' }}</td>
                            <td>{{ $task->estado ?? 'Sin estado' }}</td>
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