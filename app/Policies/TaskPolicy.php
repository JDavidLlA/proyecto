<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Throwable;

class TaskPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user, ?Project $project = null): bool
    {
        if (! $this->hasPermission($user, [
            'tasks.view',
            'tasks.index',
            'view tasks',
        ])) {
            return false;
        }

        if (! $project) {
            return true;
        }

        return $this->canAccessProject($user, $project);
    }

    public function view(User $user, Task $task): bool
    {
        if (! $this->hasPermission($user, [
            'tasks.view',
            'tasks.show',
            'view tasks',
        ])) {
            return false;
        }

        return $task->project && $this->canAccessProject($user, $task->project);
    }

    public function create(User $user, Project $project): bool
    {
        if (! $this->hasPermission($user, [
            'tasks.create',
            'create tasks',
        ])) {
            return false;
        }

        return $this->canAccessProject($user, $project);
    }

    public function update(User $user, Task $task): bool
    {
        if (! $this->hasPermission($user, [
            'tasks.update',
            'tasks.edit',
            'edit tasks',
            'update tasks',
        ])) {
            return false;
        }

        return $task->project && $this->canAccessProject($user, $task->project);
    }

    public function delete(User $user, Task $task): bool
    {
        if (! $this->hasPermission($user, [
            'tasks.delete',
            'tasks.destroy',
            'delete tasks',
        ])) {
            return false;
        }

        return $task->project && $this->canAccessProject($user, $task->project);
    }

    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }

    private function canAccessProject(User $user, Project $project): bool
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        if (isset($project->owner_id) && (int) $project->owner_id === (int) $user->id) {
            return true;
        }

        if (method_exists($project, 'members')) {
            try {
                return $project->members()
                    ->where('users.id', $user->id)
                    ->exists();
            } catch (Throwable $e) {
                return false;
            }
        }

        return false;
    }

    private function hasPermission(User $user, array $permissions): bool
    {
        if (! method_exists($user, 'hasAnyPermission')) {
            return false;
        }

        try {
            return $user->hasAnyPermission($permissions);
        } catch (Throwable $e) {
            return false;
        }
    }
}