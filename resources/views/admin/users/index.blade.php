@extends('layouts.app')

@section('title', 'Administrar usuarios')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Administrar usuarios</h1>

        <p class="page-subtitle">
            Desde aquí el administrador puede asignar o cambiar roles a los usuarios registrados.
        </p>

        <div class="actions">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                Volver al dashboard
            </a>
        </div>
    </div>

    <div class="page-card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Roles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>

                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>

                            <td>{{ $user->email }}</td>

                            <td>
                                @forelse ($user->roles as $role)
                                    @php
                                        $roleClass = match($role->name) {
                                            'admin' => 'badge-red',
                                            'lider' => 'badge-blue',
                                            'colaborador' => 'badge-green',
                                            'invitado' => 'badge-yellow',
                                            default => 'badge-blue',
                                        };
                                    @endphp

                                    <span class="badge {{ $roleClass }}">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @empty
                                    <span class="badge badge-yellow">
                                        Sin rol
                                    </span>
                                @endforelse
                            </td>

                            <td>
                                <div class="actions">
                                    @can('users.update_roles')
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                                            Cambiar rol
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    </div>
@endsection