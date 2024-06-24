<?php

namespace App\Livewire\Idea;

use App\Models\Idea;
use App\Services\Idea\IdeaVoteService;
use Livewire\Component;

class Card extends Component
{
    public $idea;

    public $hasVoted;

    public $isViewOnly;

    public $searchKeywords;

    public function mount(IdeaVoteService $ideaVoteService, Idea $idea, $isViewOnly = false, $searchKeywords = null)
    {
        $this->idea = $idea;
        $this->hasVoted = $ideaVoteService->isVotedByUser($idea, auth()->user());
        $this->isViewOnly = $isViewOnly;
        $this->searchKeywords = $searchKeywords;
    }

    public function render()
    {
        return view('livewire.idea.card');
    }
}
