@props([
    'product'
])
@if ($image = $product->getMedia('attachments')->first())
    <x-detail-banner :item="$product" :logo="true" logoSrc="{{ route('file.attachments.show', ['display', $image->file_name]) }}">
        <x-product.indicators :product="$product" />
    </x-detail-banner>
@else
    <x-detail-banner :item="$product" :logo="true" logoIntials="{{ Str::substr($product->name, 0, 2) }}">
        <x-product.indicators :product="$product" />
    </x-detail-banner>
@endif
