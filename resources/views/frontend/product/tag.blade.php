<x-frontend-layout :product="$product">
    <x-slot name="title">
        {{ $product->name }} - {{ $tag->name }}
    </x-slot>
    {{ Breadcrumbs::render('tag', $tag) }}

    <div class="mb-8">
        <x-info-box>
            Found <b>{{ $tag->ideas->count() }}</b> idea(s) from tag
            <x-button
                2xs
                rounded
                slate
            >{{ $tag->name }}</x-button>
        </x-info-box>
    </div>

    <livewire:idea.idea-cards-container
        :product="$product"
        :tag="$tag"
    />
    <!-- Modals -->
</x-frontend-layout>
