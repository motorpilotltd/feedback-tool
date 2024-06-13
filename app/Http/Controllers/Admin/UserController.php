<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Handles the management of users in the admin panel.
 */
class UserController extends Controller
{
    /**
     * Display the manage users page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users');
    }
}
