<?php

namespace App\Livewire\Idea;

use App\Models\Comment;
use App\Models\Idea;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class CommentsContainer extends Component
{
    use WithPagination;

    public $idea;

    protected $listeners = [
        'commentWasAdded',
        'commentWasDeleted',
        'statusWasUpdated',
        'refreshCommentIdea',
        'refreshCommentsContainer' => '$refresh',
    ];

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
    }

    public function refreshCommentIdea()
    {
        $this->idea->refresh();
    }

    public function commentWasAdded()
    {
        $this->idea->refresh();
        // Use to scroll to the newly added comment
        $this->dispatch('scroll-to-latest-comment', true);
    }

    public function statusWasUpdated()
    {
        $this->idea->refresh();
        // Use to scroll to the newly added comment
        $this->dispatch('scroll-to-latest-comment', true);
    }

    public function commentWasDeleted()
    {
        $this->idea->refresh();
        $this->goToPage(1);
    }

    #[Computed]
    public function comments()
    {
        return Comment::with(['user', 'status', 'media.model', 'spams'])
            ->where('idea_id', $this->idea->id)
            ->orderByDesc('comments.created_at')
            ->paginate()
            ->withQueryString();
    }

    #[Computed]
    public function testVar()
    {
        return 'foo';
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function render()
    {
        return view('livewire.idea.comments-container');
    }
}
