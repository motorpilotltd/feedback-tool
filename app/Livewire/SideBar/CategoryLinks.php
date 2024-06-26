<?php

namespace App\Livewire\SideBar;

use App\Models\Category;
use Livewire\Component;

class CategoryLinks extends Component
{
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function render()
    {
        return view('livewire.side-bar.category-links', [
            'categories' => Category::where('product_id', $this->productId)->withCount('ideas')->get(),
        ]);
    }
}
