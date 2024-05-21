<?php

namespace App\Traits\Livewire;

trait WithProductSelection
{
    public $productId = 0;

    // Executed during the Livewire Component initialization
    public function initializeWithProductSelection()
    {
        // Livewire's $listeners
        $this->listeners = array_merge($this->listeners, [
            'product:selection:selected' => 'setProductSelected'
        ]);

        $this->productId = session('admin.productId', 0);
    }

    public function setProductSelected(?int $product = 0)
    {
        $this->productId = $product;
        session(['admin.productId' => $product]);
        $this->dispatch('productUpdated')->to('admin.ideas-table');
    }


}
