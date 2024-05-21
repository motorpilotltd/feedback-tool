@props([
    'icon' => 'information-circle'
])
<span
    x-data="{ tooltip: false }"
    x-on:mouseover="tooltip = true"
    x-on:mouseleave="tooltip = false"
    class="ml-2 h-5 w-5 cursor-pointer"
>
    <x-icon :name="$icon" />
    <div x-show="tooltip"
        class="text-sm text-white absolute bg-gray-800 rounded-lg
        p-2 transform -translate-y-16 -translate-x-24 z-10"
        x-cloak
    >
        {{$slot}}
    </div>
</span>
