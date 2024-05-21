<?php

namespace App\Livewire\SideBar;

use App\Models\Product;
use App\Models\Tag;
use App\Models\TagGroup;
use Livewire\Component;

class TagsList extends Component
{
    public $product;
    public $currentTagId;

    protected $listeners = ['setActiveTag'];

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function setActiveTag($tagId)
    {
        $this->currentTagId = $tagId;
    }

    public function render()
    {
        return view('livewire.side-bar.tags-list', [
            'tagsGroup' => TagGroup::with(['tags' => function ($query) {
                $query->withCount('ideas');
            }])->where('product_id', $this->product->id)->get()
        ]);
    }
}
