@props([
    'src' => '',
    'alt' => 'avatar',
])
<img
    {{ $attributes->merge(['class' => 'w-14 h-14 rounded-xl']) }}
    src="{{ $src }}"
    alt="{{ $alt }}"
/>
