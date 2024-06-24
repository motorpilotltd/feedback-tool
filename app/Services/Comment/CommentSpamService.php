<?php

namespace App\Services\Comment;

use App\Models\Comment;
use App\Models\CommentSpam;
use App\Models\User;

class CommentSpamService
{
    public function toggleSpam(Comment $comment, ?User $user)
    {
        $isMarked = $this->userMarkCommentSpam($comment, $user);
        $isMarked ? $comment->spams()->detach($user) : $comment->spams()->attach($user);
        if (! $isMarked) {
            $comment->touch();
        }
    }

    public function userMarkCommentSpam(Comment $comment, ?User $user)
    {
        if (! $user) {
            return false;
        }

        return CommentSpam::where('user_id', $user->id)
            ->where('comment_id', $comment->id)
            ->exists();
    }
}
