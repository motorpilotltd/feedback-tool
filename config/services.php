<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'azure' => [
        'client_id' => env('AZURE_CLIENT_ID'),
        'client_secret' => env('AZURE_CLIENT_SECRET'),
        'redirect' => env('AZURE_REDIRECT_URI'),
        'tenant' => env('AZURE_TENANT_ID'),
        // 'proxy' => env('PROXY')  // optionally

        // Trust Azure App Service "Easy Auth" principal headers to log the user
        // in, skipping the in-app Socialite OAuth round-trip. ONLY enable on
        // deployments actually fronted by Easy Auth — see AuthenticateEasyAuth.
        'easy_auth' => env('AZURE_EASY_AUTH', false),
    ],

];
