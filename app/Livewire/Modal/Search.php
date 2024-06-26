<?php

namespace App\Livewire\Modal;

use Livewire\Component;

class Search extends Component
{
    public bool $showModal = false;

    public function openSearchModal()
    {
        $this->showModal = true;
        $this->dispatch('global-search-focuskeyword');
    }

    public function render()
    {
        return view('livewire.modal.search');
    }
}
