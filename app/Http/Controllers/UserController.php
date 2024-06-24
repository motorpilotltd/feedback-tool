<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\Livewire\WithDispatchNotify;
use App\Models\User;

class UserController extends Controller
{
    use WithDispatchNotify;
    /**
     * Show the profile for a given user.
     */
    public function show(User $user = null)
    {
        if ($user === null) {
        $user = auth()->user();
        }
        return view('profile.view', [
            'user' => $user
        ]);
    }
}
