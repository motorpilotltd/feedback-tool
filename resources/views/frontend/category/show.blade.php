<x-frontend-layout :product="$product">
    <x-slot name="title">
        {{ $category->name }}
    </x-slot>
    {{ Breadcrumbs::render('category', $category) }}
    <x-detail-banner :item='$category'/>

    <livewire:idea.idea-cards-container
        :product="$product"
        :currentCategory="$category->id"
    />
</x-frontend-layout>
