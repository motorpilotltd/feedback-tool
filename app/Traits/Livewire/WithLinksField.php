<?php

namespace App\Traits\Livewire;

use Illuminate\Support\Collection;

trait WithLinksField
{
    public Collection $links;

    public bool $linksHasErrors = false;

    public function mountWithLinksField()
    {
        $this->links = collect([]);
        $this->linksHasErrors = false;
    }

    public function getLinksListeners(): array
    {
        return [
            'links-field.links-updated' => 'handleLinksUpdated',
            'links-field.validation-failed' => 'handleLinksValidationFailure'
        ];
    }

    public function handleLinksUpdated($links)
    {
        $this->linksHasErrors = false;
        $this->links = collect($links);
    }

    public function handleLinksValidationFailure($errors)
    {
        $this->linksHasErrors = true;

        // Merge errors into parent's error bag
        foreach ($errors as $key => $messages) {
            foreach ($messages as $message) {
                $this->addError($key, $message);
            }
        }
    }

    protected function validateLinksBeforeSave(): bool
    {
        if ($this->linksHasErrors) {
            $this->notification()->error(
                title: 'Validation Error',
                description: 'Please fix the errors in the links section'
            );
            return false;
        }
        return true;
    }
}
