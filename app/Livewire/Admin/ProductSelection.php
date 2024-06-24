<?php

namespace App\Livewire\Admin;

use App\Traits\Livewire\WithProductManage;
use Livewire\Component;

class ProductSelection extends Component
{
    use WithProductManage;

    public $selected = '';

    public function mount()
    {
        $this->selected = session('admin.productId', '');
    }

    public function updatedSelected()
    {
        $this->dispatch('product:selection:selected', $this->selected);
    }

    public function render()
    {
        $products = $this->getProducts()->get();

        return view('livewire.admin.product-selection', [
            'products' => $products,
        ]);
    }
}
