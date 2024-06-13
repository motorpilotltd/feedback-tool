<?php

namespace App\Http\Controllers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

/**
 * Handles displaying and downloading media files.
 */
class MediaController extends Controller
{
    /**
     * Handle the incoming request to either display or download a media file.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $action
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media $media
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $action, Media $media)
    {
        if (!in_array($action, ['display', 'download'])) {
            abort(404);
        }
        return $action == 'display' ? $media->toInlineResponse($request) : $media->toResponse($request);
    }
}
