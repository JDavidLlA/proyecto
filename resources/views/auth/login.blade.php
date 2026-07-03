@extends('layouts.app')

@section('title', 'Iniciar sesión - Gestor')

@section('content')
    <div class="form-box">
        <h1>Iniciar sesión</h1>
        <p>Ingresa con tu cuenta de Gestor.</p>

        @if ($errors->any())
            <div class="alert-error">
                Revise los datos ingresados.
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST">
            @csrf

            <label for="email">Correo electrónico</label>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                required
                autofocus
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

            <label>
                <input class="checkbox" type="checkbox" name="remember">
                Recordarme
            </label>

            <button class="btn" type="submit">Entrar</button>
        </form>

        <p>
            ¿No tienes cuenta?
            <a class="link" href="{{ route('register') }}">Regístrate aquí</a>
        </p>

        <hr>

        <p><strong>Usuarios semilla para probar:</strong></p>
        <p>admin@gestor.com / password</p>
        <p>lider@gestor.com / password</p>
        <p>colaborador@gestor.com / password</p>
        <p>invitado@gestor.com / password</p>
    </div>
@endsection