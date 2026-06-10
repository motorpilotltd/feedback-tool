<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Switches an impersonation session back to the original admin account.
 */
class UserLoginAsController extends Controller
{
    /**
     * Processing logging in as different user
     */
    public function index(Request $request): RedirectResponse
    {
        // Only reachable while impersonating: admin_user is set by loginAsUser().
        $adminUser = $request->session()->get('admin_user');
        abort_unless($adminUser, 403);

        $user = User::find($adminUser->id);
        abort_unless($user, 403);

        $redirect = loginAsUser($user, true);

        return redirect()->to($redirect)->with('notify', [
            'message' => __('text.loginassucess', ['user' => $user->name]),
            'type' => 'success',
        ]);
    }
}
