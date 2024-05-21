<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia as SpatieInteractsWithMedia;


trait HasMediaCollectionsTrait
{
    use SpatieInteractsWithMedia {
        SpatieInteractsWithMedia::registerMediaCollections as registerMediaCollectionsTrait;
    }

    public function registerMediaCollections(): void
    {
        // Custom logic
        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes(config('const.ACCEPTED_IMAGE_MIMETYPES'));

        // Call the alias method to include Spatie's implementation
        $this->registerMediaCollectionsTrait();
    }
}
