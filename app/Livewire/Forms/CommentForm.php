<?php

namespace App\Livewire\Forms;

use App\Traits\Livewire\WithMediaAttachments;
use App\Models\Comment;
use App\Models\Idea;
use App\Notifications\CommentAdded;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class CommentForm extends Component
{
    use WithFileUploads, WithMediaAttachments, Actions;

    const ACTIONS = ['add', 'edit'];
    public $idea;
    public $content;
    public $comment;
    public $action;
    public $wireSubmit = '';
    public $allowedTypes = "['image/png', 'image/jpg', 'image/jpeg']";
    public $allowedSize = "2MB";
    public $maxFiles = 4;

    protected $rules = [
        'content' => 'required|min:4',
        'attachments.*' => 'image|max:2024',
    ];
    public $isRender = true;

    protected $listeners = ['setComment', 'editCommentModalClosed'];

    public function mount(Idea $idea, string $action)
    {
        $this->idea = $idea;
        $this->action = $action;
        if (!in_array($action, self::ACTIONS)) {
            $this->isRender = false;
        } else {
            $this->wireSubmit = $action == 'add' ? 'addComment' : 'updateComment';
        }
    }

    public function setComment(int $id)
    {
        if ($this->action !== 'add') {
            $this->comment = Comment::findOrFail($id);
            $this->content = $this->comment->content;
        }
    }

    public function editCommentModalClosed()
    {
        $this->resetForm();
    }

    public function addComment()
    {
        if (auth()->guest()) {
            $this->notification()->warning(
                $description = __('text.mustlogin'),
            );
        } else {
            $this->validate();

            $comment = Comment::create([
                'user_id' => auth()->id(),
                'idea_id' => $this->idea->id,
                'content' => $this->content
            ]);

            $this->storeCommentAttachments($comment);

            $this->resetForm();
            // Only notify when other users commented on the idea
            if ($this->idea->author_id !== auth()->id()) {
                $this->idea->author->notify(New CommentAdded($comment));
            }
            $this->dispatch('commentWasAdded');
            $this->dispatch('refreshIdeaShow');

            $this->notification()->success(
                $description = __('text.commentaddedsuccess'),
            );
        }
    }

    public function updateComment()
    {
        if (auth()->guest() || auth()->user()->cannot('update', $this->comment)) {
            $this->notification()->warning(
                $description = __('error.actionnotpermitted'),
            );
        } else {
            $this->validate();

            $this->comment->content = $this->content;
            $this->comment->save();

            $this->storeCommentAttachments($this->comment);

            $this->resetForm();

            $this->dispatch('closeCommentFormModal');
            $this->dispatch("comment:refresh:{$this->comment->id}");

            $this->notification()->success(
                $description = __('text.commentupdatedsuccess'),
            );
        }
    }

    public function resetForm()
    {
        $this->reset('content');
        $this->dispatch('pondReset');
    }

    public function render()
    {
        $this->displayMultipleFileErrors($this->attachments, 'attachments');
        return view('livewire.forms.comment-form');
    }
}
