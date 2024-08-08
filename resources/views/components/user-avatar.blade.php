@props([
    'user' => '',
    'avatar' => '',
    'name' => '',
])
@php
    if (empty($avatar)) {
        $avatar = $user
            ? $user->getAvatar()
            : null;
    }


    $name = isset($user->name) ? $user->name : $name;

@endphp

@if (!empty($avatar))
    <x-avatar {{$attributes}} src="{{ $avatar }}"/>
@else
    <x-avatar label="{{ Str::substr($name, 0, 2) }}" />
@endif
