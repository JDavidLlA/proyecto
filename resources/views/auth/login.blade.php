@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="card">
        <h1>Iniciar sesión</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Entrar</button>
        </form>

        <p>
            ¿No tienes cuenta?
            <a href="{{ route('register') }}">Registrarse</a>
        </p>
    </div>
@endsection