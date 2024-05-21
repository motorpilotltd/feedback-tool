@props(['active'])

<div class="bg-white mx-auto border border-gray-200 rounded"
    x-init="
        $wire.on('accordion:set:activeItem', (item) => {
            activeItem = item
        })
    "
    x-data="{
        activeItem: '{{ $active }}',
    }"
>
    <ul class="shadow-box" x-ref="items">
        {{ $slot }}
    </ul>
</div>
