@props([
    'items' => 'items'
])
<div class="mx-auto flex flex-row items-center space-x-2">
    <x-icon name="information-circle" class="text-gray-400 w-16" />
    <span class="text-2xl text-gray-500">{{ __('general.noitemsfound', ['items' => $items]) }}</span>
</div>
