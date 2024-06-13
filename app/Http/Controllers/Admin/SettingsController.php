<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Handles the system settings in the admin panel.
 */
class SettingsController extends Controller
{
    /**
     * Display the system settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.settings');
    }
}
