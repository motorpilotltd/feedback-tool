<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <x-admin.container>
    <div class="w-1/3 mb-4">
        <x-input
            wire:model.live.debounce.500ms="search"
            right-icon="magnifying-glass"
            label=""
            placeholder="Search for product..." />
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
        @foreach ($products as $product)
            <x-product-card
                :key="$product->id"
                :product="$product"
            />
        @endforeach
    </div>
    </x-admin.container>
</div>
