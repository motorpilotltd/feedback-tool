@props([
    'src' => '',
    'alt' => 'avatar',
])
<img
    {{ $attributes->merge(['class' => 'w-7 h-7 rounded']) }}
    src="{{ $src }}"
    alt="{{ $alt }}"
/>
