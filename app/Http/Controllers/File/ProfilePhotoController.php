<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

/**
 * Handles displaying profile photos.
 */
class ProfilePhotoController extends Controller
{
    /**
     * Handle the incoming request to display a profile photo.
     */
    public function show(Request $request, string $filename): Response
    {
        // Construct the file path
        $path = 'public/profile-photos/'.$filename;

        // Check if the file exists
        if (! Storage::exists($path)) {
            abort(404);
        }

        // Get the file's content
        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        // Return the file as a response
        return response($file)->header('Content-Type', $type);
    }
}
