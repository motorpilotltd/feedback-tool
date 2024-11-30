@props(['id' => null, 'maxWidth' => null])

<x-modal.card-custom :id="$id"  :title="$title" blur  {{ $attributes }}>
    <x-modal.content>
        <x-modal.row>
            {{ $content }}
        </x-modal.row>
    </x-modal.content>

    <x-slot name="footer">
        <x-modal.button-container>
            {{ $footer }}
        </x-modal.button-container>
    </x-slot>
</x-modal.card-custom>
