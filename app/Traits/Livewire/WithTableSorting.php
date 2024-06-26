<?php

namespace App\Traits\Livewire;

trait WithTableSorting
{
    public $sortDirection = 'asc';

    public $sortField = 'created_at';

    // Executed during the Livewire Component initialization
    public function initializeWithTableSorting()
    {
        // Livewire's $queryString
        $this->queryString = array_merge($this->queryString, [
            'sortField',
            'sortDirection',
        ]);
    }

    public function sortBy($field)
    {
        $this->resetPage();
        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }
}
