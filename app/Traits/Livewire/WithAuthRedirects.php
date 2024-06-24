<?php

namespace App\Traits\Livewire;

trait WithAuthRedirects
{
    public function redirectToLogin()
    {
        $this->setIntendedUrl();

        return redirect()->route('login');
    }

    public function redirectToRegister()
    {
        $this->setIntendedUrl();

        return redirect()->route('register');
    }

    public function redirectToAzureLogin()
    {
        $this->setIntendedUrl();

        return redirect()->route('auth.microsoft');
    }

    private function setIntendedUrl()
    {
        $prevurl = url()->previous();
        $referrerhost = parse_url($prevurl, PHP_URL_HOST);
        $apphost = parse_url(env('APP_URL'), PHP_URL_HOST);
        if ($referrerhost === $apphost) {
            redirect()->setIntendedUrl($prevurl);
        }
    }
}
