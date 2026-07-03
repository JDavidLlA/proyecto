@extends('layouts.app')

@section('title', 'Editar rol')

@section('content')
    <div class="page-card">
        <h1 class="page-title">Cambiar rol de usuario</h1>

        <p class="page-subtitle">
            Desde esta pantalla puedes asignar un rol al usuario seleccionado.
        </p>

        <p>
            <strong>Usuario:</strong>
            {{ $user->name }}
        </p>

        <p style="margin-top: 8px;">
            <strong>Correo:</strong>
            {{ $user->email }}
        </p>

        <p style="margin-top: 8px;">
            <strong>Rol actual:</strong>

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
        </p>
    </div>

    <div class="page-card">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="role">Nuevo rol</label>

                <select name="role" id="role" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            @selected(old('role', $user->roles->first()?->name) === $role->name)>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>

                @error('role')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    Guardar rol
                </button>

                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>

                <a href="{{ route('dashboard') }}" class="btn btn-dark">
                    Volver al dashboard
                </a>
            </div>
        </form>
    </div>
@endsection