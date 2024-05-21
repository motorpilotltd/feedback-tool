<x-app-layout>
    <x-slot name="header">
        <x-header-inner>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ideas & Comments') }}
            </h2>
            <livewire:admin.product-selection />
        </x-header-inner>
    </x-slot>

    <x-admin.container>
        <livewire:admin.ideas-table />
    </x-admin.container>
</x-app-layout>
