<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Manage Products
     *
     * @return \Illuminate\Http\Response
     */
    public function products()
    {
        return view('admin.products', []);
    }

    /**
     * System Settings
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('admin.settings', []);
    }

    /**
     * Manage Ideas
     *
     * @return \Illuminate\Http\Response
     */
    public function ideas()
    {
        return view('admin.ideas');
    }

    /**
     * Manage Categories
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        return view('admin.categories');
    }

    /**
     * Manage Tags
     *
     * @return \Illuminate\Http\Response
     */
    public function tags()
    {
        return view('admin.tags');
    }


    /**
     * Manage users
     *
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        return view('admin.users');
    }
}
