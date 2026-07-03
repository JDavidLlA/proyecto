@extends('layouts.app')

@section('title', 'Dashboard - Gestor')

@section('content')
    <div class="card">
        <h1>Panel principal</h1>
        <p>Bienvenido, <strong>{{ auth()->user()->name }}</strong>.</p>
        <p>Este panel solo es visible para usuarios autenticados.</p>
    </div>

    <div class="grid">
        <div class="stat">
            <h3>Usuarios</h3>
            <p>{{ $totalUsers }}</p>
        </div>

        <div class="stat">
            <h3>Proyectos</h3>
            <p>{{ $totalProjects }}</p>
        </div>

        <div class="stat">
            <h3>Tareas</h3>
            <p>{{ $totalTasks }}</p>
        </div>

        <div class="stat">
            <h3>Comentarios</h3>
            <p>{{ $totalComments }}</p>
        </div>
    </div>

    <div class="card">
        <h2>Últimos proyectos</h2>

        @if ($latestProjects->count())
            <table>
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Estado</th>
                        <th>Dueño</th>
                        <th>Tareas</th>
                        <th>Miembros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestProjects as $project)
                        <tr>
                            <td>{{ $project->nombre }}</td>
                            <td>{{ ucfirst($project->estado) }}</td>
                            <td>{{ $project->owner->name ?? 'Sin dueño' }}</td>
                            <td>{{ $project->tasks_count }}</td>
                            <td>{{ $project->members_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay proyectos registrados.</p>
        @endif
    </div>
@endsection