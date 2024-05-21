<x-frontend-layout :product="$product">
    <x-slot name="title">
        {{ $product->name }}
    </x-slot>
    {{ Breadcrumbs::render('product', $product) }}
    <x-product.detail-banner :product="$product" />

    <livewire:idea.idea-cards-container
        :product="$product"
    />

    <!-- Modals -->
    <x-modal.modals-container :product="$product"/>

</x-frontend-layout>
