<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

/**
 * Handles the management of categories in the admin panel.
 */
class CategoryController extends Controller
{
    /**
     * Display the manage categories page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        return view('admin.categories');
    }
}
