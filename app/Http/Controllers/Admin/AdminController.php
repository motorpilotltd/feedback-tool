<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Manage Products
     *
     * @return \Illuminate\Http\Response
     */
    public function products(): View
    {
        return view('admin.products', []);
    }

    /**
     * System Settings
     *
     * @return \Illuminate\Http\Response
     */
    public function settings(): View
    {
        return view('admin.settings', []);
    }

    /**
     * Manage Ideas
     *
     * @return \Illuminate\Http\Response
     */
    public function ideas(): View
    {
        return view('admin.ideas');
    }

    /**
     * Manage Categories
     *
     * @return \Illuminate\Http\Response
     */
    public function categories(): View
    {
        return view('admin.categories');
    }

    /**
     * Manage Tags
     *
     * @return \Illuminate\Http\Response
     */
    public function tags(): View
    {
        return view('admin.tags');
    }

    /**
     * Manage users
     *
     * @return \Illuminate\Http\Response
     */
    public function users(): View
    {
        return view('admin.users');
    }
}
