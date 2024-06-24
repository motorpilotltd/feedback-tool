<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\Livewire\WithDispatchNotify;

class UserController extends Controller
{
    use WithDispatchNotify;

    /**
     * Show the profile for a given user.
     */
    public function show(Request $request, ?User $user = null)
    {
        if ($user === null) {
            $user = $request->user();
        }

        return view('profile.view', [
            'user' => $user,
        ]);
    }

    public function loginAs(Request $request)
    {
        $adminUser = $request->session()->get('admin_user');
        $user = User::find($adminUser->id);
        $redirect = loginAsUser($user, true);

        return redirect($redirect)->with('notify', [
            'message' => __('text.loginassucess', ['user' => $user->name]),
            'type' => 'success',
        ]);
    }
}
