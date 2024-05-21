<?php

namespace App\Traits;

use App\Settings\GeneralSettings;

trait WithPerPage
{
    protected $perPageMax = 10;

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage(): int
    {
        $pagination = app(GeneralSettings::class)->pagination;
        return $pagination ?? $this->perPageMax;
    }

    /**
     * Set the maximum items per page
     *
     * @param int $perPageMax
     */
    public function setPerPageMax(int $perPageMax): void
    {
        $this->perPageMax = $perPageMax;
    }
}
