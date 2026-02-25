@props([
    'product' => null
])
@if (!empty($product))
    <div class="flex flex-row items-center">
        {{-- Indicate archived product --}}
        @if ($product->isArchived())
            <span class="ml-2 inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                {{ __('text.archived') }}
            </span>
        @endif
        {{-- Indicate product in-sandbox mode --}}
        @if (isset($product->settings['enableSandboxMode']) && $product->settings['enableSandboxMode'])
            <x-tooltip-beta icon="cube">{{ __('text.tooltip:sandboxmode') }}</x-tooltip-beta>
        @endif
        @can($product->permission)
            <x-tooltip-beta icon="shield-check">{{ __('text.tooltip:productadmin') }}</x-tooltip-beta>
        @endcan
    </div>
@endif
