<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Throwable;

class CommentPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user, Task $task): bool
    {
        return $this->hasPermission($user, [
            'comments.view',
            'comments.index',
            'view comments',
        ]);
    }

    public function view(User $user, Comment $comment): bool
    {
        return $this->hasPermission($user, [
            'comments.view',
            'comments.show',
            'view comments',
        ]);
    }

    public function create(User $user, Task $task): bool
    {
        return $this->hasPermission($user, [
            'comments.create',
            'create comments',
        ]);
    }

    public function update(User $user, Comment $comment): bool
    {
        if (! $this->hasPermission($user, [
            'comments.update',
            'comments.edit',
            'edit comments',
            'update comments',
        ])) {
            return false;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('lider')) {
            return true;
        }

        return (int) $comment->user_id === (int) $user->id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if (! $this->hasPermission($user, [
            'comments.delete',
            'comments.destroy',
            'delete comments',
        ])) {
            return false;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('lider')) {
            return true;
        }

        return (int) $comment->user_id === (int) $user->id;
    }

    public function restore(User $user, Comment $comment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
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