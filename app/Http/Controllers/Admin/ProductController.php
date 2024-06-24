<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

/**
 * Handles the management of products in the admin panel.
 */
class ProductController extends Controller
{
    /**
     * Display the manage products page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        return view('admin.products');
    }
}
