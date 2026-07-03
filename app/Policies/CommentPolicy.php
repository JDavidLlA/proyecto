<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
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
        return $user->can('comments.view');
    }

    public function view(User $user, Comment $comment): bool
    {
        return $user->can('comments.view');
    }

    public function create(User $user): bool
    {
        return $user->can('comments.create');
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->can('comments.update') && (
            $user->hasRole('lider') ||
            $this->ownsComment($user, $comment)
        );
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->can('comments.delete') && (
            $user->hasRole('lider') ||
            $this->ownsComment($user, $comment)
        );
    }

    public function restore(User $user, Comment $comment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
        return false;
    }

    private function ownsComment(User $user, Comment $comment): bool
    {
        return ! is_null($comment->getAttribute('user_id'))
            && (int) $comment->getAttribute('user_id') === (int) $user->id;
    }
}