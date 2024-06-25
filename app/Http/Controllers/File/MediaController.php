<?php

namespace App\Http\Controllers\File;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Handles displaying and downloading media files.
 */
class MediaController extends Controller
{
    /**
     * Handle the incoming request to either display or download a media file.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $action, Media $media)
    {
        if (! in_array($action, ['display', 'download'])) {
            abort(404);
        }

        return $action == 'display' ? $media->toInlineResponse($request) : $media->toResponse($request);
    }
}
