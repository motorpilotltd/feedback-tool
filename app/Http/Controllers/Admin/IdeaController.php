<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

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
