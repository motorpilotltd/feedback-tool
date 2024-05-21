<?php

namespace App\Traits\Livewire;

trait WithModelEditing
{
    public function setEditing($model)
    {
        if($this->editing->isNot($model)) $this->editing = $model; // Preserved form data when ESC pressed or cancel clicked
    }
}
