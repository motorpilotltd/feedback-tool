<div class="product-select flex left-0 md:w-1/3 lg:w-1/5 w-full mt-4 md:mt-0">
        <span class="p-1.5">Product</span>
        <x-select
            wire.key="selectionProductId"
            id="selectProductId"
            wire:model.live="selected"
            placeholder="{{ __('Select a product..') }}"
            :options="$products"
            option-label="name"
            option-value="id"
            :searchable="true"
            min-items-for-search="5"
        />
</div>
