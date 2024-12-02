<div id="search-modal">
    <x-mini-button rounded icon="magnifying-glass" wire:click='openSearchModal' />
    <x-modal id="globalSearchModal" blur wire:model.defer="showModal">
        <x-card>
            <livewire:global-search />
        </x-card>
    </x-modal>
</div>
