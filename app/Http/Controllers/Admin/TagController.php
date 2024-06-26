<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Handles the management of tags in the admin panel.
 */
class TagController extends Controller
{
    /**
     * Display the manage tags page.
     */
    public function index(): View
    {
        return view('admin.tags');
    }
}
