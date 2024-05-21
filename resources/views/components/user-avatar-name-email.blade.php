@props([
    'user' => '',
])
@php
    $deleted = $user ? false : true;
    $email
@endphp
<div class="flex items-center">
        <div class="flex-shrink-0 h-10 w-10 items-center mt-1">
            @if(!$deleted)
                <x-user-avatar sm :user="$user" />
            @else
                <x-avatar sm />
            @endif
        </div>
        <div class="ml-4">
        <div class="text-sm font-medium  @if($deleted) text-gray-500 @else text-gray-900 @endif">
            {!! $user->name ?? __('text.userdeleted') !!}
        </div>
        <div class="text-sm text-gray-500">
            {!! $user->email ?? null !!}
        </div>
    </div>
</div>
