<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;

class ViewProfile extends Component
{
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.user.view-profile', [
            'ideas' => $this->user->authoredIdeas
        ]);
    }
}
