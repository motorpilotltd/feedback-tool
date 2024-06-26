<?php

namespace App\Livewire;

use App\Settings\LinksSettings;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowLinksDropdown extends Component
{
    public Collection $links;

    public $title;

    public function mount()
    {
        $linksSettings = resolve(LinksSettings::class);
        $this->links = collect($linksSettings->links);
        $this->title = $linksSettings->title;
    }

    public function render()
    {
        return view('livewire.show-links-dropdown');
    }
}
