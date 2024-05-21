@props([
    'product' => null
])
<div class="border-b-2 py-2 border-gray-100 flex flex-row justify-center space-x-8">
    <a href="{{ route('product.suggest.idea', $product) }}">
        <x-badge.circle lg info icon="chat" class="hover:bg-blue-400 cursor-pointer" />
    </a>
    @if (!empty($product->settings['serviceDeskLink']))
        <a href="{{ $product->settings['serviceDeskLink'] }}">
            <x-badge.circle lg info class="hover:bg-blue-400 cursor-pointer"><x-icon.bug class="text-white" /></x-badge.circle>
        </a>
    @endif
    <a href="{{ route('product.progress', $product) }}">
        <x-badge.circle lg info icon="presentation-chart-line" class="hover:bg-blue-400 cursor-pointer" />
    </a>

</div>
