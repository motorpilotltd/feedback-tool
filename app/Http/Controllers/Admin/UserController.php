<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

/**
 * Handles the management of users in the admin panel.
 */
class UserController extends Controller
{
    /**
     * Display the manage users page.
     */
    public function index(): View
    {
        return view('admin.users');
    }
}
