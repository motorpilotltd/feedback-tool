<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class Select2Dropdown extends Component
{
    public $value = '';

    public $name = 'select2';

    public $default = '';

    public $placeholder = 'Select...';

    public Collection $options;

    public function mount($name, $options, $default, $placeholder)
    {
        $this->name = $name;
        $this->options = $options;
        $this->default = $default;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('livewire.select-dropdown');
    }
}
