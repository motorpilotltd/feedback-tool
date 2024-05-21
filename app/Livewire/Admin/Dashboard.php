<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\Product\ProductFilterService;
use App\Models\Product;

class Dashboard extends Component
{
    public $search = '';

    public function mount()
    {
        $this->search = request()->search ?: '';
    }

    public function getProductsProperty()
    {
        return (new ProductFilterService)->filter($this->search)->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard',[
            'products' => $this->products
        ])
        ->layout('layouts.app');
    }
}
