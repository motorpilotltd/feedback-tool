@props([
    'product' => null
])
@if (!empty($product))
    <div class="flex flex-row">
        {{-- Indicate product in-sandbox mode --}}
        @if (isset($product->settings['enableSandboxMode']) && $product->settings['enableSandboxMode'])
            <x-tooltip-beta icon="cube">{{ __('text.tooltip:sandboxmode') }}</x-tooltip-beta>
        @endif
        @can($product->permission)
            <x-tooltip-beta icon="shield-check">{{ __('text.tooltip:productadmin') }}</x-tooltip-beta>
        @endcan
    </div>
@endif
