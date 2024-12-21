<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class LinksField extends Component
{
    public Collection $links;

    public $newLink = [
        'label' => '',
        'url' => '',
    ];

    protected $listeners = ['populate-links' => 'populateLinks'];

    private function getValidationRules(): array
    {
        return [
            'label' => 'required|string',
            'url' => 'required|url',
        ];
    }

    private function getValidationMessages(): array
    {
        return [
            'label.required' => __('error.fieldisrequired', ['field' => 'Link label']),
            'url.required' => __('error.fieldisrequired', ['field' => 'Link url']),
            'url.url' => 'Link url must be a valid URL format',
        ];
    }

    private function validateLinks(): \Illuminate\Validation\Validator
    {
        $rules = collect($this->getValidationRules())
            ->mapWithKeys(fn ($rule, $field) => ["links.*.$field" => $rule])
            ->toArray();

        $messages = collect($this->getValidationMessages())
            ->mapWithKeys(fn ($message, $key) => ["links.*.$key" => $message])
            ->toArray();

        return Validator::make(
            ['links' => $this->links->toArray()],
            $rules,
            $messages
        );
    }

    public function mount(Collection $initialLinks)
    {
        $this->links = $initialLinks;
    }

    public function populateLinks($links)
    {
        $this->links = collect($links);
    }

    public function updatedLinks()
    {
        $validator = $this->validateLinks();

        if ($validator->fails()) {
            $this->setErrorBag($validator->errors());
            $this->dispatch('links-field.validation-failed', errors: $validator->errors()->toArray());
        } else {
            $this->resetErrorBag();
            $this->dispatch('links-field.links-updated', $this->links);
        }
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
