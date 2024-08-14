<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

if (! function_exists('convertLocalToUTC')) {
    function getPreviousQueryString()
    {
        $prevUrl = parse_url(URL::previous());
        $query = $prevUrl['query'] ?? '';
        parse_str($query, $resultQuery);

        return $resultQuery;
    }
}

if (! function_exists('getPreviousRouteName')) {
    function getPreviousRouteName()
    {
        $previousUrl = url()->previous();
        $previousHost = parse_url($previousUrl, PHP_URL_HOST);
        $currentHost = parse_url(url('/'), PHP_URL_HOST);

        // Only attempt to get the previous route name if the host is the same
        if ($previousHost === $currentHost) {
            return app('router')
                ->getRoutes()
                ->match(app('request')->create($previousUrl))
                ->getName();
        }

        // Return null or a default value if it's an external URL
        return null;
    }
}

if (! function_exists('loginAsUser')) {
    function loginAsUser(User $user, $isAdmin = false)
    {
        $adminUser = auth()->user();
        session()->flush();

        Auth::login($user);
        if (! $isAdmin) {
            session()->put('admin_user', $adminUser);
        }

        return url()->previous();
    }
}

if (! function_exists('hideEmailAddress')) {
    function hideEmailAddress($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            [$first, $last] = explode('@', $email);
            $first = str_replace(substr($first, '3'), str_repeat('*', strlen($first) - 3), $first);
            $last = explode('.', $last);
            $last_domain = str_replace(substr($last['0'], '1'), str_repeat('*', strlen($last['0']) - 1), $last['0']);
            $hideEmailAddress = $first.'@'.$last_domain.'.'.$last['1'];

            return $hideEmailAddress;
        }
    }
}

if (! function_exists('highlightMatchedSearch')) {
    function highlightMatchedSearch($text, $keyword)
    {
        $keyword = trim($keyword);
        // Split the keyword into individual words
        $keywords = explode(' ', $keyword);

        // Iterate over each keyword and highlight it in the text
        foreach ($keywords as $word) {
            $word = preg_quote($word, '/');
            // Use case-insensitive matching for highlighting
            $text = preg_replace("/($word)/i", '<span class="bg-yellow-200">$1</span>', $text);
        }

        return $text;
    }
}
