<?php

namespace App\Livewire\Admin;

use App\Settings\AzureADSettings;
use App\Settings\GeneralSettings;
use App\Settings\LinksSettings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class SystemSettings extends Component
{
    public $generalSettings;

    public $azureadSettings;

    public $linksSettings;

    public Collection $links;

    protected $listeners = ['links-field.links-updated' => 'linksUpdated'];

    public function mount()
    {
        $settings = $this->getGeneralSettings();
        $this->generalSettings = $settings->toCollection();

        $settings = $this->getAzureADSettings();
        $this->azureadSettings = $settings->toCollection();

        $this->linksSettings = $this->getLinksSettings()->toCollection();
        $this->links = collect($this->linksSettings->get('links'));
    }

    public function saveGeneralSettings()
    {
        $settings = $this->getGeneralSettings();
        Validator::make($this->generalSettings->toArray(), [
            'divert_email' => ['string', 'email', 'max:255'],
            'app_email' => ['string', 'email', 'max:255'],
        ])->validate();

        $this->generalSettings->each(function ($value, $key) use (&$settings) {
            $settings->{$key} = $value;
        });
        $this->dispatch('savedGeneralSettings');
        $settings->save();
    }

    public function saveAzureADSettings()
    {
        $settings = $this->getAzureADSettings();
        $this->azureadSettings->each(function ($value, $key) use (&$settings) {
            $settings->{$key} = $value;
        });
        $this->dispatch('savedAzureadSettings');
        $settings->save();
    }

    public function linksUpdated($links)
    {
        $this->links = collect($links);
    }

    public function saveLinksSettings()
    {
        $settings = $this->getLinksSettings();
        $settings->title = $this->linksSettings->get('title');
        $settings->links = $this->links->toArray();
        $settings->save();

        $this->dispatch('savedLinksSettings');
    }

    protected function getGeneralSettings()
    {
        return resolve(GeneralSettings::class);
    }

    protected function getAzureADSettings()
    {
        return resolve(AzureADSettings::class);
    }

    protected function getLinksSettings()
    {
        return resolve(LinksSettings::class);
    }

    public function render()
    {
        return view('livewire.admin.system-settings');
    }
}
