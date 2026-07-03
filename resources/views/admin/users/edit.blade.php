@extends('layouts.app')

@section('title', 'Editar rol')

@section('content')
    <div class="card">
        <h1>Cambiar rol de usuario</h1>

        <p>
            Usuario:
            <strong>{{ $user->name }}</strong>
            |
            {{ $user->email }}
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Rol</label>

                <select name="role" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            @selected($user->hasRole($role->name))>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit">Guardar rol</button>

            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </form>
    </div>
@endsection