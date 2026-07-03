@extends('layouts.app')

@section('content')
<div style="max-width: 1100px; margin: 30px auto;">
    <h1>Tareas del proyecto</h1>

    <p>
        <strong>Proyecto:</strong>
        {{ $project->nombre ?? $project->titulo ?? $project->name ?? ('Proyecto #' . $project->id) }}
    </p>

    @if (session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 20px; display: flex; gap: 10px;">
        <a href="{{ route('projects.show', $project) }}"
           style="padding: 10px 15px; background: #6b7280; color: white; text-decoration: none; border-radius: 8px;">
            Volver al proyecto
        </a>

        @can('create', [\App\Models\Task::class, $project])
            <a href="{{ route('projects.tasks.create', $project) }}"
               style="padding: 10px 15px; background: #2563eb; color: white; text-decoration: none; border-radius: 8px;">
                Nueva tarea
            </a>
        @endcan
    </div>

    <table style="width: 100%; border-collapse: collapse; background: white;">
        <thead>
            <tr style="background: #f3f4f6;">
                <th style="border: 1px solid #ddd; padding: 10px;">ID</th>
                <th style="border: 1px solid #ddd; padding: 10px;">Título</th>
                <th style="border: 1px solid #ddd; padding: 10px;">Estado</th>
                <th style="border: 1px solid #ddd; padding: 10px;">Fecha límite</th>
                <th style="border: 1px solid #ddd; padding: 10px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 10px;">{{ $task->id }}</td>
                    <td style="border: 1px solid #ddd; padding: 10px;">{{ $task->titulo }}</td>
                    <td style="border: 1px solid #ddd; padding: 10px;">
                        {{ str_replace('_', ' ', ucfirst($task->estado)) }}
                    </td>
                    <td style="border: 1px solid #ddd; padding: 10px;">
                        {{ $task->fecha_limite ? $task->fecha_limite->format('d/m/Y') : 'Sin fecha' }}
                    </td>
                    <td style="border: 1px solid #ddd; padding: 10px;">
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            @can('view', $task)
                                <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                   style="padding: 7px 10px; background: #0f766e; color: white; text-decoration: none; border-radius: 6px;">
                                    Ver
                                </a>
                            @endcan

                            @can('update', $task)
                                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                                   style="padding: 7px 10px; background: #ca8a04; color: white; text-decoration: none; border-radius: 6px;">
                                    Editar
                                </a>
                            @endcan

                            @can('delete', $task)
                                <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar esta tarea?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            style="padding: 7px 10px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                        Eliminar
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="border: 1px solid #ddd; padding: 15px; text-align: center;">
                        No hay tareas registradas para este proyecto.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $tasks->links() }}
    </div>
</div>
@endsection