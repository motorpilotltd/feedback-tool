<x-app-layout>
    <x-slot name="header" class="flex">
        <x-header-inner>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categories') }}
            </h2>
            <livewire:admin.product-selection />

        </x-header-inner>
    </x-slot>

    <x-admin.container>
        <livewire:admin.categories-table />
    </x-admin.container>
</x-app-layout>
