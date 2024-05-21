<x-mail::message>
Hi {{ $user->name }},

Your _{{ config('app.name') }}_ account has been successfully created.

**Email:** {{ $user->email }}

<x-mail::button url="{{ route('login') }}">
Login Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
