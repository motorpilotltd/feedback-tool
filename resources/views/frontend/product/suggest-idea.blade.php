<x-frontend-layout :product="$product">
    <x-slot name="title">
        {{ $product->name }} - {{ __('Suggest an idea...') }}
    </x-slot>
    {{ Breadcrumbs::render('suggestIdea', $product) }}
    <x-product.detail-banner :product="$product" />

    {{-- Content --}}
    <livewire:forms.idea-form :product="$product"/>

    <!-- Modals -->
        <x-modal.modals-container :product="$product"/>

</x-frontend-layout>
