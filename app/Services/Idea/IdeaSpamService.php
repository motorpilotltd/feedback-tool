<?php

namespace App\Services\Idea;

use App\Models\Idea;
use App\Models\IdeaSpam;
use App\Models\User;

class IdeaSpamService
{
    public function userMarkIdeaSpam(Idea $idea, ?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return IdeaSpam::where('user_id', $user->id)
            ->where('idea_id', $idea->id)
            ->exists();
    }

    public function toggleSpam(Idea $idea, User $user): void
    {
        $isMarked = $this->userMarkIdeaSpam($idea, $user);
        $isMarked ? $idea->spams()->detach($user) : $idea->spams()->attach($user);
        if (! $isMarked) {
            $idea->touch();
        }
    }

    public function clearSpam(Idea $idea): void
    {
        $idea->spams()->sync([]);
    }
}
