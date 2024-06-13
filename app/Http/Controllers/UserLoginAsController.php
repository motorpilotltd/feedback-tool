<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;

/**
 * Handles the management of tags in the admin panel.
 */
class UserLoginAsController extends Controller
{
    /**
     * Processing logging in as different user
     *
     * @return void
     */
    public function index()
    {
        $adminUser = session()->get('admin_user');
        $user = User::find($adminUser->id);
        $redirect = loginAsUser($user, true);

        return redirect($redirect)->with('notify', [
            'message' => __('text.loginassucess', ['user' => $user->name]),
            'type' => 'success'
        ]);
    }
}
