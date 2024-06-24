<div>
    <x-welcome />
    <div class="flex flex-col">
        <h1 class="font-medium text-4xl mb-2">{{ __('product.products') }}</h1>
        <div class="text-gray-800 text-lg">{{ __('product.select_a_product') }}</div>
    </div>
    <div class="products-container space-y-6 my-6">
        <div class="w-full mb-4 flex flex-row space-x-2">
            <x-input
                wire:model.live.debounce.500ms="search"
                right-icon="search"
                label=""
                placeholder="Search for product..." />

            <span
                x-data="{ tooltip: false }"
                x-on:mouseover="tooltip = true"
                x-on:mouseleave="tooltip = false"
                class="cursor-pointer"
            >
                <x-button.circle info :icon="'view-'.$viewMode" wire:click="toggleViewMode"/>
                <div x-show="tooltip"
                    class="text-sm text-white absolute bg-gray-800 rounded-lg p-2 transform translate-y-0.5 z-10 -translate-x-14"
                    x-cloak
                >
                    {{ __($viewMode == 'grid' ? 'text.tooltip:listview': 'text.tooltip:gridview') }}
                </div>
            </span>
        </div>
        <div
            class="@if ($viewMode == 'list')
                    space-y-6
                @else
                    grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4
                @endif"
            wire:loading.class.delay="opacity-30"
            wire:target='search,sortBy'
        >
        @if ($viewMode == 'list')
            @forelse ($products as $product)
                <livewire:product.container
                    :key="'list-'.$product->id"
                    :product="$product"
                />
            @empty
                <x-no-items-available :items="__('products')" />
            @endforelse
        @else
            @forelse ($products as $product)
                <x-product-card
                    :key="'grid-'.$product->id"
                    :product="$product"
                />
            @empty
                <x-no-items-available :items="__('products')" />
            @endforelse
        @endif

        </div>
    </div>

    <div class="my-8">
        {{ $products->links() }}
    </div>
</div>
