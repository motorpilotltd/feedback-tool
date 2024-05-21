<?php

namespace App\Livewire\Idea;

use Livewire\Component;

class SearchBar extends Component
{
    public $search;

    public function mount()
    {
        $this->search = request()->search ?: '';
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 3) {
            $this->dispatch('ideaQuerySearch', $this->search);
            $this->dispatch('searchAsTitle', e($this->search));
        } else if (strlen($this->search) < 3) {
            $this->dispatch('ideaQuerySearch', '');
        }

    }

    public function render()
    {
        return view('livewire.idea.search-bar');
    }
}
