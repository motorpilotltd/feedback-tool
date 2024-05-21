<?php

namespace App\Livewire\SideBar;

use Livewire\Component;
use App\Models\User;
use App\Models\Product;

class People extends Component
{
    public $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function getPeopleProperty()
    {
        return User::permission(config('const.PERMISSION_PRODUCTS_MANAGE') . '.' . $this->product->id)->get();
    }

    public function render()
    {
        return view('livewire.side-bar.people', [
            'people' => $this->people
        ]);
    }
}
