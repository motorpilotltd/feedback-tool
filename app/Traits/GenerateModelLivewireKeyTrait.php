<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia as SpatieInteractsWithMedia;

/**
 * Generates a unique key, resolves issue on pagination when using Pipelined query
 *
 * src: https://github.com/livewire/livewire/issues/1686#issuecomment-698503788
 */
trait GenerateModelLivewireKeyTrait
{
    public function getForLivewireKey () {
        return time().$this->id;
    }

}
