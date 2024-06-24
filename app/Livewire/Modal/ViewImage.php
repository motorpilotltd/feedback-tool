<?php

namespace App\Livewire\Modal;

use Livewire\Component;

class ViewImage extends Component
{
    public $imageUrl;

    protected $listeners = ['setImageUrl'];

    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        $this->dispatch('view-image-modal');
    }

    public function render()
    {
        return view('livewire.modal.view-image');
    }
}
