<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Traits\Livewire\WithDispatchNotify;
use Livewire\Component;

class LoginAs extends Component
{
    use WithDispatchNotify;

    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function loginUser(User $user)
    {
        $redirect = loginAsUser($user);
        $this->sessionNotifySuccess(__('text.loginassucess', ['user' => $user->name]));
        redirect($redirect);
    }

    public function render()
    {
        return view('livewire.user.login-as');
    }
}
