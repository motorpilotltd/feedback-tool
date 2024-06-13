<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Handles the management of ideas in the admin panel.
 */
class IdeaController extends Controller
{
    /**
     * Display the manage ideas page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.ideas');
    }
}
