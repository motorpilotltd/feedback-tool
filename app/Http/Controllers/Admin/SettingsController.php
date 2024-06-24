<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
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
    public function index(): View
    {
        return view('admin.settings');
    }
}
