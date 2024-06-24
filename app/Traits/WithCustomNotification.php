<?php

namespace App\Traits;

trait WithCustomNotification
{
    public $customType;

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function databaseType()
    {
        return $this->customType ?? get_class($this);
    }
}
