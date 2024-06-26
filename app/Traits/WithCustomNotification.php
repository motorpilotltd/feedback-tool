<?php

namespace App\Traits;

trait WithCustomNotification
{
    public $customType;

    /**
     * Get the type of the notification being broadcast.
     */
    public function databaseType(): string
    {
        return $this->customType ?? get_class($this);
    }
}
