<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Handles the management of categories in the admin panel.
 */
class CategoryController extends Controller
{
    /**
     * Display the manage categories page.
     */
    public function index(): View
    {
        return view('admin.categories');
    }
}
