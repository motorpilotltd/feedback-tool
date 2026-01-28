<?php

namespace App\Traits\Livewire;

trait WithAuthRedirects
{
    public function redirectToLogin()
    {
        $this->setIntendedUrl();

        return to_route('login');
    }

    public function redirectToRegister()
    {
        $this->setIntendedUrl();

        return to_route('register');
    }

    public function redirectToAzureLogin()
    {
        $this->setIntendedUrl();

        return to_route('auth.microsoft');
    }

    private function setIntendedUrl()
    {
        $prevurl = url()->previous();
        $referrerhost = parse_url($prevurl, PHP_URL_HOST);
        $apphost = parse_url(config('app.url'), PHP_URL_HOST);
        if ($referrerhost === $apphost) {
            redirect()->setIntendedUrl($prevurl);
        }
    }
}
