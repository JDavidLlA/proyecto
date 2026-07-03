@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <h1>Dashboard privado</h1>

        <p>
            Bienvenido, <strong>{{ auth()->user()->name }}</strong>.
        </p>

        <p>
            Rol actual:
            <strong>{{ auth()->user()->getRoleNames()->join(', ') }}</strong>
        </p>
    </div>

    <div class="card">
        <h2>Controles por rol</h2>

        @role('admin')
            <p>Estás viendo este bloque porque eres administrador.</p>
            <a href="{{ route('admin.users.index') }}" class="btn">Administrar usuarios</a>
        @endrole

        @role('lider')
            <p>Estás viendo este bloque porque eres líder de proyecto.</p>
        @endrole

        @role('colaborador')
            <p>Estás viendo este bloque porque eres colaborador.</p>
        @endrole

        @role('invitado')
            <p>Estás viendo este bloque porque eres invitado.</p>
        @endrole
    </div>

    <div class="card">
        <h2>Controles por permisos</h2>

        @can('projects.create')
            <a href="#" class="btn">Crear proyecto</a>
        @endcan

        @can('tasks.create')
            <a href="#" class="btn">Crear tarea</a>
        @endcan

        @can('comments.create')
            <a href="#" class="btn">Crear comentario</a>
        @endcan

        @cannot('users.update_roles')
            <p>No tienes permiso para cambiar roles de usuarios.</p>
        @endcannot
    </div>
@endsection