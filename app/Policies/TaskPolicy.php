<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('tasks.view');
    }

    public function view(User $user, Task $task): bool
    {
        return $user->can('tasks.view') && $this->belongsToTaskProject($user, $task);
    }

    public function create(User $user): bool
    {
        return $user->can('tasks.create');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->can('tasks.update') && (
            $user->hasRole('lider') ||
            $this->ownsTask($user, $task) ||
            $this->belongsToTaskProject($user, $task)
        );
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->can('tasks.delete') && (
            $user->hasRole('lider') ||
            $this->ownsTask($user, $task)
        );
    }

    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }

    private function ownsTask(User $user, Task $task): bool
    {
        return ! is_null($task->getAttribute('user_id'))
            && (int) $task->getAttribute('user_id') === (int) $user->id;
    }

    private function belongsToTaskProject(User $user, Task $task): bool
    {
        if ($this->ownsTask($user, $task)) {
            return true;
        }

        if (! method_exists($task, 'project')) {
            return false;
        }

        $project = $task->project;

        if (! $project) {
            return false;
        }

        if (! is_null($project->getAttribute('user_id'))
            && (int) $project->getAttribute('user_id') === (int) $user->id) {
            return true;
        }

        if (method_exists($project, 'members')) {
            return $project->members()
                ->where('users.id', $user->id)
                ->exists();
        }

        return false;
    }
}