<?php

namespace App\Livewire\SideBar;

use App\Models\Product;
use Livewire\Component;

class Container extends Component
{
    public $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }
    public function render()
    {
        return view('livewire.side-bar.container');
    }
}
