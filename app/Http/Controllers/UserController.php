<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Livewire\WithDispatchNotify;

class UserController extends Controller
{
    use WithDispatchNotify;

    /**
     * Show the profile for a given user.
     */
    public function show(?User $user = null)
    {
        if ($user === null) {
            $user = auth()->user();
        }

        return view('profile.view', [
            'user' => $user,
        ]);
    }

    public function loginAs()
    {
        $adminUser = session()->get('admin_user');
        $user = User::find($adminUser->id);
        $redirect = loginAsUser($user, true);

        return redirect($redirect)->with('notify', [
            'message' => __('text.loginassucess', ['user' => $user->name]),
            'type' => 'success',
        ]);
    }
}
