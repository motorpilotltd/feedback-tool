<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Handles the management of tags in the admin panel.
 */
class UserLoginAsController extends Controller
{
    /**
     * Processing logging in as different user
     */
    public function index(Request $request): RedirectResponse
    {
        $adminUser = $request->session()->get('admin_user');
        $user = User::find($adminUser->id);
        $redirect = loginAsUser($user, true);

        return redirect()->to($redirect)->with('notify', [
            'message' => __('text.loginassucess', ['user' => $user->name]),
            'type' => 'success',
        ]);
    }
}
