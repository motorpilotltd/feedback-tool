<?php

namespace App\Livewire;

use App\Settings\AzureADSettings;
use App\Traits\Livewire\WithAuthRedirects;
use Exception;
use Livewire\Component;

class LoginRegisterLinks extends Component
{
    use WithAuthRedirects;

    public function render()
    {
        try {
            $aadOnly = app(AzureADSettings::class)->aad_only;
        } catch (Exception $e) {
            $aadOnly = false;
        }

        return view('livewire.login-register-links', [
            'azureOnly' => $aadOnly,
        ]);
    }
}
