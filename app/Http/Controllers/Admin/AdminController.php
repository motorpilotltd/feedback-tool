<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Manage Products
     */
    public function products(): View
    {
        return view('admin.products', []);
    }

    /**
     * System Settings
     */
    public function settings(): View
    {
        return view('admin.settings', []);
    }

    /**
     * Manage Ideas
     */
    public function ideas(): View
    {
        return view('admin.ideas');
    }

    /**
     * Manage Categories
     */
    public function categories(): View
    {
        return view('admin.categories');
    }

    /**
     * Manage Tags
     */
    public function tags(): View
    {
        return view('admin.tags');
    }

    /**
     * Manage users
     */
    public function users(): View
    {
        return view('admin.users');
    }
}
