<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Handles the management of products in the admin panel.
 */
class ProductController extends Controller
{
    /**
     * Display the manage products page.
     */
    public function index(): View
    {
        return view('admin.products');
    }
}
