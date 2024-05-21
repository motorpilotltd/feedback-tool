<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use App\Settings\LinksSettings;

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
