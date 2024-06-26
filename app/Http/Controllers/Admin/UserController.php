<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

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
