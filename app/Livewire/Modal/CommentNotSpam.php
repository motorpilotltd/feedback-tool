<?php

namespace App\Livewire\Modal;

use App\Traits\Livewire\WithDispatchNotify;
use App\Models\Comment;
use Livewire\Component;

class CommentNotSpam extends Component
{
    use WithDispatchNotify;

    public $comment;
    protected $listeners = ['commentNotSpamModal'];

    public function commentNotSpamModal($id)
    {
        $this->comment = Comment::findOrFail($id);

        $this->dispatch('openCommentNotSpamModal');
    }

    public function commentNotSpam()
    {
        if (auth()->guest()) {
            $this->dispatchNotifyWarning(__('error.actionnotpermitted'));
        } else {
            $this->comment->spams()->sync([]);
            $this->dispatchNotifySuccess(__('text.removespamsuccess'));
        }

        $this->dispatch('commentMarkedAsNotSpam');
        $this->dispatch("comment:refresh:{$this->comment->id}");

    }

    public function render()
    {
        return view('livewire.modal.comment-not-spam');
    }
}
