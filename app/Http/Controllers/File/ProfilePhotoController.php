<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

/**
 * Handles displaying profile photos.
 */
class ProfilePhotoController extends Controller
{
    /**
     * Handle the incoming request to display a profile photo.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $filename)
    {
        // Construct the file path
        $path = 'public/profile-photos/' . $filename;

        // Check if the file exists
        if (!Storage::exists($path)) {
            abort(404);
        }

        // Get the file's content
        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        // Return the file as a response
        return response($file, 200)->header("Content-Type", $type);
    }
}
