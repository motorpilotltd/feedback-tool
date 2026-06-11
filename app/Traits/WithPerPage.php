<?php

namespace App\Traits;

use App\Settings\GeneralSettings;

trait WithPerPage
{
    protected $perPageMax = 10;

    /**
     * Get the number of models to return per page.
     */
    public function getPerPage(): int
    {
        $pagination = app(GeneralSettings::class)->pagination;

        return $pagination ?? $this->perPageMax;
    }
}
