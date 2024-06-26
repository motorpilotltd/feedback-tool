<?php

namespace App\Livewire\Idea;

use App\Models\Idea;
use App\Services\Idea\IdeaVoteService;
use App\Traits\Livewire\WithAuthRedirects;
use Livewire\Component;

class VotesCountButton extends Component
{
    use WithAuthRedirects;

    public $ideaId;

    public $hasVoted;

    public $votesCount;

    public $isResponsive;

    public $isHorizontal;

    public function mount(int $ideaId, bool $hasVoted, int $votesCount, $isResponsive = false, $isHorizontal = false): void
    {
        $this->ideaId = $ideaId;
        $this->hasVoted = $hasVoted;
        $this->votesCount = $votesCount;
        $this->isResponsive = $isResponsive;
        $this->isHorizontal = $isHorizontal;
    }

    public function voteIdea(IdeaVoteService $ideaVoteService)
    {
        if (auth()->guest()) {
            return $this->redirectToLogin();
        }
        $idea = Idea::find($this->ideaId);

        $ideaVoteService->toggleVote($idea, auth()->user());

        if ($this->hasVoted) {
            $this->votesCount--;
            $this->hasVoted = false;
        } else {
            $this->votesCount++;
            $this->hasVoted = true;
        }
    }

    public function render()
    {
        return view('livewire.idea.votes-count-button');
    }
}
