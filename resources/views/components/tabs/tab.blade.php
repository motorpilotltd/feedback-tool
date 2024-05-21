@props(['name', 'tabLink'])

<div x-data="{
        name: '{{ $name }}',
        tabLink: '{{ $tabLink }}',
        show: false,
    }"
    x-show="'#'+ tabLink == activeTab"
>
    {{ $slot }}
</div>
