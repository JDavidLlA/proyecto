@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 30px auto;">
    <h1>Detalle de tarea</h1>

    @if (session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border: 1px solid #ddd; border-radius: 10px; padding: 20px;">
        <p>
            <strong>Proyecto:</strong>
            {{ $project->nombre ?? ('Proyecto #' . $project->id) }}
        </p>

        <p>
            <strong>Título:</strong>
            {{ $task->titulo }}
        </p>

        <p>
            <strong>Descripción:</strong><br>
            {{ $task->descripcion ?: 'Sin descripción' }}
        </p>

        <p>
            <strong>Estado:</strong>
            {{ str_replace('_', ' ', ucfirst($task->estado)) }}
        </p>

        <p>
            <strong>Fecha límite:</strong>
            {{ $task->fecha_limite ? $task->fecha_limite->format('d/m/Y') : 'Sin fecha' }}
        </p>
    </div>

    <div style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('projects.tasks.index', $project) }}"
           style="padding: 10px 15px; background: #6b7280; color: white; text-decoration: none; border-radius: 8px;">
            Volver a tareas
        </a>

        @can('update', $task)
            <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
               style="padding: 10px 15px; background: #ca8a04; color: white; text-decoration: none; border-radius: 8px;">
                Editar tarea
            </a>
        @endcan

        @can('delete', $task)
            <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                  method="POST"
                  onsubmit="return confirm('¿Seguro que deseas eliminar esta tarea?');">
                @csrf
                @method('DELETE')

                <button type="submit"
                        style="padding: 10px 15px; background: #dc2626; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Eliminar tarea
                </button>
            </form>
        @endcan
    </div>

    <div style="background: white; border: 1px solid #ddd; border-radius: 10px; padding: 20px; margin-top: 25px;">
        <h2>Comentarios</h2>

        @can('create', [\App\Models\Comment::class, $task])
            <form action="{{ route('projects.tasks.comments.store', [$project, $task]) }}" method="POST" style="margin-bottom: 25px;">
                @csrf

                <div style="margin-bottom: 12px;">
                    <label for="cuerpo">Nuevo comentario</label>
                    <textarea name="cuerpo"
                              id="cuerpo"
                              rows="4"
                              style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;"
                              required>{{ old('cuerpo') }}</textarea>
                </div>

                @error('cuerpo')
                    <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 12px;">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit"
                        style="padding: 10px 15px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Guardar comentario
                </button>
            </form>
        @endcan

        @if ($task->comments->count() > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f3f4f6;">
                        <th style="border: 1px solid #ddd; padding: 10px;">Usuario</th>
                        <th style="border: 1px solid #ddd; padding: 10px;">Comentario</th>
                        <th style="border: 1px solid #ddd; padding: 10px;">Fecha</th>
                        <th style="border: 1px solid #ddd; padding: 10px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($task->comments as $comment)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 10px;">
                                {{ $comment->user->name ?? 'Usuario eliminado' }}
                            </td>

                            <td style="border: 1px solid #ddd; padding: 10px;">
                                {{ $comment->cuerpo }}
                            </td>

                            <td style="border: 1px solid #ddd; padding: 10px;">
                                {{ $comment->created_at?->format('d/m/Y H:i') }}
                            </td>

                            <td style="border: 1px solid #ddd; padding: 10px;">
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    @can('view', $comment)
                                        <a href="{{ route('projects.tasks.comments.show', [$project, $task, $comment]) }}"
                                           style="padding: 7px 10px; background: #0f766e; color: white; text-decoration: none; border-radius: 6px;">
                                            Ver
                                        </a>
                                    @endcan

                                    @can('update', $comment)
                                        <a href="{{ route('projects.tasks.comments.edit', [$project, $task, $comment]) }}"
                                           style="padding: 7px 10px; background: #ca8a04; color: white; text-decoration: none; border-radius: 6px;">
                                            Editar
                                        </a>
                                    @endcan

                                    @can('delete', $comment)
                                        <form action="{{ route('projects.tasks.comments.destroy', [$project, $task, $comment]) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar este comentario?');">
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
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Esta tarea todavía no tiene comentarios.</p>
        @endif
    </div>
</div>
@endsection