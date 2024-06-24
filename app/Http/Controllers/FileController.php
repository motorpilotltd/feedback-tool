<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileController extends Controller
{
    public function show(Request $request, string $action, Media $media)
    {
        if (! in_array($action, ['display', 'download'])) {
            abort(404);
        }

        return $action == 'display' ? $media->toInlineResponse($request) : $media->toResponse($request);
    }

    public function showProfilePhoto(Request $request, string $filename)
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
        return response($file, 200)->header('Content-Type', $type);
    }
}
