<?php

namespace App\Livewire\Modal;

use App\Models\Comment;
use App\Models\Idea;
use Livewire\Component;

class EditComment extends Component
{
    public $idea;
    public $action;
    public $test;
    public $commentId;

    protected $listeners = ['setEditComment'];

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
        $this->action = 'edit';
    }

    public function setEditComment($id)
    {
        $this->dispatch('setComment', $id)->to('forms.comment-form');
        $this->commentId = $id;
        $this->dispatch('open-edit-comment-modal');
    }

    public function render()
    {
        return view('livewire.modal.edit-comment');
    }
}
