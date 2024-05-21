<x-frontend-layout :product="$product">
    <x-slot name="title">
        {{ $idea->title }}
    </x-slot>
    {{ Breadcrumbs::render('idea', $idea) }}
    <x-product.detail-banner :product="$product" />
    <!-- Displaying Idea -->
    <livewire:idea.idea-show :idea="$idea->id" />
    <!-- Idea Comments -->
    <livewire:idea.comments-container :idea='$idea->id'/>
    <!-- Modals -->
    <x-modal.modals-container :product="$product" :idea='$idea'/>

</x-frontend-layout>
