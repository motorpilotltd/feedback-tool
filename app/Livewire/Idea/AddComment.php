<?php

namespace App\Livewire\Idea;

use App\Traits\Livewire\WithAuthRedirects;
use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Http\Response;
use Livewire\Component;

class AddComment extends Component
{
    use WithAuthRedirects;

    public $idea;
    public $action;

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
        $this->action = 'add';
    }

    public function render()
    {
        return view('livewire.idea.add-comment');
    }
}
