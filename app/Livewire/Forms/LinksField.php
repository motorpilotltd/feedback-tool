<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class LinksField extends Component
{
    public Collection $links;

    public $newLink = ['label' => '', 'url' => ''];

    protected $listeners = ['populate-links' => 'populateLinks'];

     // When wire model binding, $rules is required
    protected function rules()
    {
        return [
            'links.*.label' => 'required|string',
            'links.*.url' => 'required|url',
        ];
    }


    protected function messages()
    {
        return [
            'links.*.label.required' => __('error.fieldisrequired', ['field' => 'Link label']),
            'links.*.url.required' =>  __('error.fieldisrequired', ['field' => 'Link url']),
            'links.*.url.url' => 'Link url must be a valid URL format',
        ];
    }

    public function mount(Collection $initialLinks)
    {
        $this->links = $initialLinks;
    }

    public function populateLinks($links)
    {
        $this->links = collect($links);
    }

    public function refreshLinksCollection()
    {
        $this->validate();
        $this->dispatch('links-field.links-updated', $this->links);
    }

    public function addLinkFields()
    {
        $this->links->push($this->newLink);
    }

    public function removeLinkFields($key)
    {
        $this->links->pull($key);
        $this->dispatch('links-field.links-updated', $this->links);
    }

    public function render()
    {
        return view('livewire.forms.links-field');
    }
}
