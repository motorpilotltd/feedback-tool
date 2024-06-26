<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Handles the management of ideas in the admin panel.
 */
class IdeaController extends Controller
{
    /**
     * Display the manage ideas page.
     */
    public function index(): View
    {
        return view('admin.ideas');
    }
}
