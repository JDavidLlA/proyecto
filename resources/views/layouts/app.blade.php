<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestor')</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f4f6;
            color: #111827;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: #111827;
            color: white;
            padding: 14px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
            color: white;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .nav-link {
            color: #d1d5db;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #2563eb;
            color: white;
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .user-info {
            font-size: 13px;
            color: #d1d5db;
            text-align: right;
        }

        .user-info strong {
            color: white;
        }

        .logout-form {
            display: inline;
        }

        .logout-btn {
            border: none;
            background: #dc2626;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 26px 18px;
        }

        .page-card,
        .card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 22px;
        }

        .page-card.priority-high,
        .card.priority-high {
            border-left: 6px solid #f59e0b;
            background: #fffbeb;
        }

        .page-card.priority-urgent,
        .card.priority-urgent {
            border-left: 6px solid #dc2626;
            background: #fef2f2;
        }

        .page-title {
            font-size: 28px;
            margin-bottom: 8px;
            color: #111827;
        }

        .page-subtitle {
            color: #6b7280;
            margin-bottom: 18px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #111827;
        }

        h2 {
            font-size: 22px;
            margin-bottom: 12px;
            color: #111827;
        }

        p {
            line-height: 1.5;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-weight: 600;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 5px solid #16a34a;
        }

        .alert-error,
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 5px solid #dc2626;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 5px solid #f59e0b;
        }

        .btn,
        button[type="submit"] {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn:hover,
        button[type="submit"]:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-dark {
            background: #111827;
            color: white;
        }

        button[type="submit"]:not(.logout-btn):not(.btn-danger):not(.btn-secondary):not(.btn-warning):not(.btn-success):not(.btn-dark) {
            background: #2563eb;
            color: white;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #111827;
            color: white;
            text-align: left;
            padding: 12px;
            font-size: 14px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        tr:hover {
            background: #f9fafb;
        }

        tr.priority-high {
            background: #fffbeb;
            border-left: 5px solid #f59e0b;
        }

        tr.priority-urgent {
            background: #fef2f2;
            border-left: 5px solid #dc2626;
        }

        tr.priority-high:hover {
            background: #fef3c7;
        }

        tr.priority-urgent:hover {
            background: #fee2e2;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 6px;
            margin-bottom: 14px;
        }

        label {
            font-weight: bold;
            color: #374151;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: -8px;
            margin-bottom: 10px;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-green {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-yellow {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-orange {
            background: #ffedd5;
            color: #c2410c;
        }

        .badge-red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-gray {
            background: #f3f4f6;
            color: #4b5563;
        }

        .priority-message-high {
            margin-top: 4px;
            color: #d97706;
            font-size: 13px;
            font-weight: bold;
        }

        .priority-message-urgent {
            margin-top: 4px;
            color: #dc2626;
            font-size: 13px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            color: #6b7280;
            padding: 25px;
            font-size: 13px;
        }

        @media (max-width: 700px) {
            .navbar {
                align-items: flex-start;
            }

            .user-info {
                text-align: left;
            }

            .main-container {
                padding: 18px 12px;
            }

            .page-title,
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

    @auth
        <nav class="navbar">
            <div>
                <a href="{{ route('dashboard') }}" class="brand">
                    Gestor
                </a>
            </div>

            <div class="nav-links">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>

                @can('projects.view')
                    <a href="{{ route('projects.index') }}"
                       class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                        Proyectos
                    </a>
                @endcan

                @can('users.view')
                    @if(\Illuminate\Support\Facades\Route::has('admin.users.index'))
                        <a href="{{ route('admin.users.index') }}"
                           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            Usuarios
                        </a>
                    @endif
                @endcan
            </div>

            <div class="user-box">
                <div class="user-info">
                    <strong>{{ auth()->user()->name }}</strong><br>

                    @if(auth()->user()->roles->count())
                        Rol: {{ auth()->user()->roles->pluck('name')->join(', ') }}
                    @else
                        Sin rol
                    @endif
                </div>

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf

                    <button type="submit" class="logout-btn">
                        Salir
                    </button>
                </form>
            </div>
        </nav>
    @endauth

    <main class="main-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <strong>Revisa los siguientes errores:</strong>

                <ul style="margin-left: 20px; margin-top: 8px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        Gestor - Proyecto Final INF560
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('form[data-confirm-delete="true"]');

            deleteForms.forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    const message = form.getAttribute('data-confirm-message') || '¿Seguro que deseas eliminar este registro?';

                    if (!confirm(message)) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>

</body>
</html>