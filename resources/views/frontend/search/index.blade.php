<x-frontend-layout>
    <x-slot name="title">
        {{ __('Search') }}
    </x-slot>
    {{ Breadcrumbs::render('search') }}
    <div class="search-fullpage mb-8">
        <livewire:global-search :isFullPage="true" :keywords="$keywords ?? ''"/>
    </div>
</x-frontend-layout>
