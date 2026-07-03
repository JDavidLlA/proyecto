<?php

use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;
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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);

    Route::patch('/projects/{project}/complete', [ProjectController::class, 'complete'])
        ->name('projects.complete');

    Route::get('/projects/{project}/members', [ProjectMemberController::class, 'index'])
        ->name('projects.members.index');

    Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])
        ->name('projects.members.store');

    Route::put('/projects/{project}/members/{user}', [ProjectMemberController::class, 'update'])
        ->name('projects.members.update');

    Route::delete('/projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])
        ->name('projects.members.destroy');

    Route::patch('/projects/{project}/tasks/{task}/complete', [TaskController::class, 'complete'])
        ->name('projects.tasks.complete');

    Route::resource('projects.tasks', TaskController::class);

    Route::resource('projects.tasks.comments', CommentController::class)
        ->except(['index']);
});

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