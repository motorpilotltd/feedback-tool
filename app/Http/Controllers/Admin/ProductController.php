<?php

namespace App\Http\Controllers\Admin;

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
    public function index()
    {
        return view('admin.products');
    }
}
