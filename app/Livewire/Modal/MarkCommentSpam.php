<?php

namespace App\Livewire\Modal;

use App\Traits\Livewire\WithDispatchNotify;
use App\Models\Comment;
use App\Services\Comment\CommentSpamService;
use Livewire\Component;

class MarkCommentSpam extends Component
{
    use WithDispatchNotify;

    public $comment;
    public $title;
    public $description;
    public $hasMarkedSpam;
    protected $listeners = ['markCommentSpamModal'];

    public function markCommentSpamModal($id, $hasMarkedSpam = false)
    {
        $this->hasMarkedSpam = $hasMarkedSpam;
        $this->title = $hasMarkedSpam ? __('text.unmarkcommentspam') : __('text.markcommentspam') ;
        $this->description = $hasMarkedSpam ? __('text.unmarksommentspamconfirm') :__('text.marksommentspamconfirm');
        $this->comment = Comment::findOrFail($id);

        $this->dispatch('openMarkCommentSpamModal');
    }

    public function markCommentSpam(CommentSpamService $commentSpamService)
    {
        if (auth()->guest()) {
            $type = 'warning';
            $this->dispatchNotifyWarning(__('error.actionnotpermitted'));
        } else {
            $commentSpamService->toggleSpam($this->comment, auth()->user());
            $message = $this->hasMarkedSpam ? __('text.commentunmarkedspamsuccess') : __('text.commentmarkedspamsuccess');
            $this->dispatchNotifySuccess($message);
        }

        $this->dispatch('commentWasMarkedAsSpam');
        $this->dispatch("comment:refresh:{$this->comment->id}");
    }

    public function render()
    {
        return view('livewire.modal.mark-comment-spam');
    }
}
