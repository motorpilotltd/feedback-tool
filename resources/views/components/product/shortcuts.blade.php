@props([
    'product' => null
])
<div class="border-b-2 py-2 border-gray-100 flex flex-row justify-center space-x-8">
    <a href="{{ route('product.suggest.idea', $product) }}">
        <x-mini-badge rounded lg info icon="chat-bubble-oval-left-ellipsis" class="hover:bg-blue-400 cursor-pointer" />
    </a>
    @if (!empty($product->settings['serviceDeskLink']))
        <a href="{{ $product->settings['serviceDeskLink'] }}">
            <x-mini-badge rounded lg info class="hover:bg-blue-400 cursor-pointer"><x-icon.bug class="text-white" /></x-mini-badge>
        </a>
    @endif
    <a href="{{ route('product.progress', $product) }}">
        <x-mini-badge rounded lg info icon="presentation-chart-line" class="hover:bg-blue-400 cursor-pointer" />
    </a>

</div>
