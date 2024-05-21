<x-frontend-layout :product="$product">
    <x-slot name="title">
        {{ $product->name }}
    </x-slot>

    {{ Breadcrumbs::render('editIdea', $idea) }}
    <x-product.detail-banner :product="$product" />
    {{-- Content --}}
    <livewire:forms.idea-form :product="$product" :idea="$idea"/>
    <!-- Modals -->
    <x-modal.modals-container :product="$product"/>

</x-frontend-layout>
