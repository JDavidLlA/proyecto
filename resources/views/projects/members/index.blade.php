@extends('layouts.app')

@section('title', 'Miembros del proyecto')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Miembros del proyecto</h1>

        <p class="page-subtitle">
            Desde aquí puedes asignar usuarios al proyecto y definir su rol dentro del proyecto.
        </p>

        <p>
            <strong>Proyecto:</strong>
            {{ $project->nombre }}
        </p>

        <div class="actions" style="margin-top: 18px;">
            <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                Volver al proyecto
            </a>

            <a href="{{ route('projects.index') }}" class="btn btn-dark">
                Volver a proyectos
            </a>
        </div>
    </div>

    <div class="page-card">
        <h2>Asignar usuario al proyecto</h2>

        <p class="page-subtitle">
            Admin puede asignar usuarios a cualquier proyecto. Líder solo puede asignar usuarios en proyectos que administra.
        </p>

        <form action="{{ route('projects.members.store', $project) }}" method="POST">
            @csrf

            <div>
                <label for="user_id">Usuario</label>

                <select name="user_id" id="user_id" required>
                    <option value="">Seleccione un usuario</option>

                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                            {{ $user->name }} - {{ $user->email }}
                            @if($user->roles->count())
                                ({{ $user->roles->pluck('name')->join(', ') }})
                            @endif
                        </option>
                    @endforeach
                </select>

                @error('user_id')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="project_role">Rol dentro del proyecto</label>

                <select name="project_role" id="project_role" required>
                    @foreach ($projectRoles as $valor => $texto)
                        <option value="{{ $valor }}" @selected(old('project_role', 'colaborador') === $valor)>
                            {{ $texto }}
                        </option>
                    @endforeach
                </select>

                @error('project_role')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    Asignar usuario
                </button>
            </div>
        </form>
    </div>

    <div class="page-card">
        <h2>Usuarios asignados</h2>

        <p class="page-subtitle">
            Lista de usuarios que pertenecen actualmente a este proyecto.
        </p>

        @if ($project->members->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol general</th>
                            <th>Rol en proyecto</th>
                            <th>Actualizar rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($project->members as $member)
                            @php
                                $memberProjectRole = $member->pivot->project_role ?? 'colaborador';

                                $roleBadgeClass = match ($memberProjectRole) {
                                    'lider' => 'badge-blue',
                                    'colaborador' => 'badge-green',
                                    'invitado' => 'badge-yellow',
                                    default => 'badge-gray',
                                };
                            @endphp

                            <tr>
                                <td>
                                    <strong>{{ $member->name }}</strong>

                                    @if ((int) $project->owner_id === (int) $member->id)
                                        <div style="margin-top: 4px; font-size: 13px; color: #2563eb; font-weight: bold;">
                                            Dueño del proyecto
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $member->email }}
                                </td>

                                <td>
                                    @if($member->roles->count())
                                        {{ $member->roles->pluck('name')->join(', ') }}
                                    @else
                                        Sin rol
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $roleBadgeClass }}">
                                        {{ $projectRoles[$memberProjectRole] ?? ucfirst($memberProjectRole) }}
                                    </span>
                                </td>

                                <td>
                                    <form action="{{ route('projects.members.update', [$project, $member]) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <select name="project_role" required>
                                            @foreach ($projectRoles as $valor => $texto)
                                                <option value="{{ $valor }}" @selected($memberProjectRole === $valor)>
                                                    {{ $texto }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="btn btn-warning">
                                            Guardar rol
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    @if ((int) $project->owner_id === (int) $member->id)
                                        <span class="badge badge-blue">
                                            No se puede quitar
                                        </span>
                                    @else
                                        <form action="{{ route('projects.members.destroy', [$project, $member]) }}"
                                              method="POST"
                                              data-confirm-delete="true"
                                              data-confirm-message="¿Seguro que deseas quitar este usuario del proyecto?">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger">
                                                Quitar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>
                Este proyecto todavía no tiene usuarios asignados.
            </p>
        @endif
    </div>
@endsection