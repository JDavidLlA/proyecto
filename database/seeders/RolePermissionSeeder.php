<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'users.view',
            'users.update_roles',

            'projects.view',
            'projects.create',
            'projects.update',
            'projects.delete',

            'tasks.view',
            'tasks.create',
            'tasks.update',
            'tasks.delete',

            'comments.view',
            'comments.create',
            'comments.update',
            'comments.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $lider = Role::firstOrCreate([
            'name' => 'lider',
            'guard_name' => 'web',
        ]);

        $colaborador = Role::firstOrCreate([
            'name' => 'colaborador',
            'guard_name' => 'web',
        ]);

        $invitado = Role::firstOrCreate([
            'name' => 'invitado',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions($permissions);

        $lider->syncPermissions([
            'projects.view',
            'projects.create',
            'projects.update',

            'tasks.view',
            'tasks.create',
            'tasks.update',
            'tasks.delete',

            'comments.view',
            'comments.create',
            'comments.update',
            'comments.delete',
        ]);

        $colaborador->syncPermissions([
            'projects.view',

            'tasks.view',
            'tasks.update',

            'comments.view',
            'comments.create',
            'comments.update',
            'comments.delete',
]);

        $invitado->syncPermissions([
            'projects.view',
            'tasks.view',
            'comments.view',
        ]);

        User::where('email', 'admin@gestor.com')->first()?->syncRoles(['admin']);
        User::where('email', 'lider@gestor.com')->first()?->syncRoles(['lider']);
        User::where('email', 'colaborador@gestor.com')->first()?->syncRoles(['colaborador']);
        User::where('email', 'invitado@gestor.com')->first()?->syncRoles(['invitado']);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}