@extends('layouts.app')

@section('title', 'Administrar usuarios')

@section('content')
    <div class="card">
        <h1>Administrar usuarios</h1>

        <p>Desde aquí el administrador puede asignar roles a los usuarios.</p>
    </div>

    <div class="card">
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
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @forelse ($user->roles as $role)
                                <span class="badge">{{ $role->name }}</span>
                            @empty
                                <span class="badge">Sin rol</span>
                            @endforelse
                        </td>
                        <td>
                            @can('users.update_roles')
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn">
                                    Cambiar rol
                                </a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    </div>
@endsection