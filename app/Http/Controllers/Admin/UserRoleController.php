<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index(): View
    {
        Gate::authorize('users.view');

        $users = User::with('roles')
            ->orderBy('id')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        Gate::authorize('users.update_roles');

        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('users.update_roles');

        $data = $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        if ($request->user()->id === $user->id && $data['role'] !== 'admin') {
            return back()->withErrors([
                'role' => 'No puedes quitarte tu propio rol de administrador.',
            ]);
        }

        $user->syncRoles([$data['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Rol actualizado correctamente.');
    }
}