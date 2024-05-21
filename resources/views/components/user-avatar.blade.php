@props([
    'user' => '',
])
@php
    $avatar = $user
        ? $user->getAvatar()
        : null;
@endphp

@if ($avatar)
    <x-avatar {{$attributes}} src="{{ $avatar }}"/>
@else
    <x-avatar label="{{ Str::upper(Str::substr($user->name, 0, 2)) }}" />
@endif
