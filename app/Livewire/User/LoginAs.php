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
        // Impersonation is super-admin only. The blade gate already hides this
        // component, but the action must enforce it server-side too.
        abort_unless(auth()->user()?->hasRole(config('const.ROLE_SUPER_ADMIN')), 403);

        $redirect = loginAsUser($user);
        $this->sessionNotifySuccess(__('text.loginassucess', ['user' => $user->name]));

        return redirect()->to($redirect);
    }

    public function render()
    {
        return view('livewire.user.login-as');
    }
}
