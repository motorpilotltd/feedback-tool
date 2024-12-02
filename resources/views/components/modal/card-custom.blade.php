<x-modal-card
    {{ $attributes->merge([]) }}
    padding="px-4 py-5 md:px-6"
>
    {{ $slot }}

    <x-slot name="footer">
        {{ $footer }}
    </x-slot>
</x-modal-card>
