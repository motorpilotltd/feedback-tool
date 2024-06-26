<?php

namespace App\Livewire\Idea;

use App\Models\Idea;
use App\Traits\Livewire\WithAuthRedirects;
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
