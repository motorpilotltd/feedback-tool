<x-frontend-layout :product="$product">
    <x-slot name="title">
        {{ $product->name }} - {{ __('Progress') }}
    </x-slot>
    {{ Breadcrumbs::render('progress', $product) }}
    <x-product.detail-banner :product="$product" />

    {{-- Content --}}
    <livewire:product.ideas-progress :product="$product" />

    {{-- Render to side-bar --}}
    @section('sidebar')
        @parent
        <livewire:side-bar.container :product='$product' />
    @stop

    <!-- Modals -->
        <x-modal.modals-container :product="$product"/>

</x-frontend-layout>
