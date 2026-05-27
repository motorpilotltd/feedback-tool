<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Handles displaying profile photos.
 */
class ProfilePhotoController extends Controller
{
    /**
     * Handle the incoming request to display a profile photo.
     */
    public function show(Request $request, string $filename): StreamedResponse
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $path = 'profile-photos/'.$filename;

        if (! $disk->exists($path)) {
            abort(404);
        }

        return $disk->response($path);
    }
}
