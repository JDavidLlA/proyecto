@extends('layouts.app')

@section('title', 'Registro - Gestor')

@section('content')
    <div class="form-box">
        <h1>Crear cuenta</h1>
        <p>Registra un nuevo usuario en Gestor.</p>

        @if ($errors->any())
            <div class="alert-error">
                Revise los datos ingresados.
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <label for="name">Nombre</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                required
                autofocus
            >
            @error('name')
                <div class="alert-error">{{ $message }}</div>
            @enderror

            <label for="email">Correo electrónico</label>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                required
            >
            @error('email')
                <div class="alert-error">{{ $message }}</div>
            @enderror

            <label for="password">Contraseña</label>
            <input
                type="password"
                name="password"
                id="password"
                required
            >
            @error('password')
                <div class="alert-error">{{ $message }}</div>
            @enderror

            <label for="password_confirmation">Confirmar contraseña</label>
            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                required
            >

            <button class="btn" type="submit">Registrarme</button>
        </form>

        <p>
            ¿Ya tienes cuenta?
            <a class="link" href="{{ route('login') }}">Inicia sesión</a>
        </p>
    </div>
@endsection