<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Handles the system settings in the admin panel.
 */
class SettingsController extends Controller
{
    /**
     * Display the system settings page.
     */
    public function index(): View
    {
        return view('admin.settings');
    }
}
