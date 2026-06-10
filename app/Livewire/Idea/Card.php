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
        // IdeaFilterService already attaches a voted_by_user subselect, so use it
        // when present to avoid one vote query per card on the listing. Fall back
        // to the service for cards rendered outside that query.
        $this->hasVoted = array_key_exists('voted_by_user', $idea->getAttributes())
            ? (bool) $idea->voted_by_user
            : $ideaVoteService->isVotedByUser($idea, auth()->user());
        $this->isViewOnly = $isViewOnly;
        $this->searchKeywords = $searchKeywords;
    }

    public function render()
    {
        return view('livewire.idea.card');
    }
}
