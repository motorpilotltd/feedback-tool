<?php

namespace App\Traits;

use App\Observers\UniqueSoftDeleteObserver;

trait AvoidDuplicateConstraintSoftDelete
{
    public static function bootAvoidDuplicateConstraintSoftDelete()
    {
        static::whenBooted(fn () => static::observe(app(UniqueSoftDeleteObserver::class)));
    }

    public function getDuplicateAvoidColumns(): array
    {
        return [];
    }
}
