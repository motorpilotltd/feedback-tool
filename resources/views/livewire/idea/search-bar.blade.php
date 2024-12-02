<div class="w-full  @if (empty($search) || (!empty($search) && strlen($search) >= 3)) mb-8 @else mb-3 @endif">
    <div class="relative">
        <input
            wire:model.live.debounce.500ms="search"
            type="search"
            name="search"
            placeholder="{{ __("general.suggestidea") }}"
            class="bg-white w-full rounded-xl border-none px-4 py-2 pr-12 text-3xl font-light focus:outline-none hover:shadow-card">
        <div class="absolute top-0 right-0 flex items-center h-full mr-4">
            <x-icon name="magnifying-glass" class="w-7 h-7" />
        </div>
    </div>
    @if (!empty($search) && strlen($search) < 3)
        <div class="text-gray-500 text-sm">{{ __('general.typethreemorechar') }}</div>
    @endif
</div>
