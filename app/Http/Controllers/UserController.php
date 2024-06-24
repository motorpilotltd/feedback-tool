<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Livewire\WithDispatchNotify;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    use WithDispatchNotify;

    /**
     * Show the profile for a given user.
     */
    public function show(Request $request, ?User $user = null): View
    {
        if ($user === null) {
            $user = $request->user();
        }

        return view('profile.view', [
            'user' => $user,
        ]);
    }
}
