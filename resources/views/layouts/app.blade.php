<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Gestor')</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #111827;
        }

        nav {
            background: #111827;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }

        nav button {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 6px;
        }

        main {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        button, .btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 9px 14px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary {
            background: #6b7280;
        }

        .btn-danger {
            background: #dc2626;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .badge {
            background: #e5e7eb;
            padding: 4px 8px;
            border-radius: 20px;
            display: inline-block;
            margin: 2px;
        }
    </style>
</head>
<body>
    <nav>
        <div>
            <strong>Gestor</strong>

            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>

                @role('admin')
                    <a href="{{ route('admin.users.index') }}">Usuarios</a>
                @endrole
            @endauth
        </div>

        <div>
            @auth
                <span>
                    {{ auth()->user()->name }}
                    |
                    {{ auth()->user()->getRoleNames()->join(', ') }}
                </span>

                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Registro</a>
            @endauth
        </div>
    </nav>

    <main>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>