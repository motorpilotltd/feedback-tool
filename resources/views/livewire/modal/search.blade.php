<div id="search-modal">
    <x-button.circle icon="search" wire:click='openSearchModal' />
    <x-modal id="globalSearchModal" blur wire:model.defer="showModal">
        <x-card>
            <livewire:global-search />
        </x-card>
    </x-modal>
</div>
