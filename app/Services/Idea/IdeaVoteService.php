<?php

namespace App\Services\Idea;

use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;

class IdeaVoteService
{
    public function isVotedByUser(Idea $idea, ?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return Vote::where('user_id', $user->id)
            ->where('idea_id', $idea->id)
            ->exists();
    }

    public function toggleVote(Idea $idea, ?User $user): void
    {
        $voted = $this->isVotedByUser($idea, $user);

        if ($voted) {
            $idea->votes()->detach($user);
        } else {
            $idea->votes()->attach($user);
            $idea->touch();
        }
    }
}
