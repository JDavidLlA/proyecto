<?php

use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware('auth')
    ->name('dashboard');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/usuarios', [UserRoleController::class, 'index'])
            ->name('users.index')
            ->middleware('permission:users.view');

        Route::get('/usuarios/{user}/editar', [UserRoleController::class, 'edit'])
            ->name('users.edit')
            ->middleware('permission:users.update_roles');

        Route::put('/usuarios/{user}', [UserRoleController::class, 'update'])
            ->name('users.update')
            ->middleware('permission:users.update_roles');
    });