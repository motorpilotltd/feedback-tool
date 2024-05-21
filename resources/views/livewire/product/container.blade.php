<div
    x-data
    @click="const clicked = $event.target
        const target = clicked.tagName.toLowerCase()
        const ignores = ['button', 'svg', 'path', 'a']
        if (! ignores.includes(target)) {
            clicked.closest('.product-container').querySelector('.product-link').click()
        }
    "
    class="product-container hover:shadow-card transition duration-150 ease-in bg-white rounded-xl flex cursor-pointer"
>
    {{-- @dump($product->getMedia('attachments')->first()) --}}
    <div class="w-full flex flex-row justify-between mx-2 md:mx-4 py-4 px-4">
        <div class="flex-none mx-2 md:mx-0">
            @if ($image = $product->getMedia('attachments')->first())
                <x-avatar lg squared src="{{ route('file.attachments.show', ['display', $image->file_name]) }}" />
            @else
                <x-avatar lg squared label="{{ Str::substr($product->name, 0, 2) }}" />
            @endif

        </div>
        <div class="w-full flex flex-col justify-between mx-2">
            <h4 class="text-3xl text-blue-base flex flex-row justify-between">
                <a href="{{ route('product.show', $product) }}" class="product-link hover:underline">{{ $product->name }}</a>
                <x-product.indicators :product="$product" />
            </h4>
            <div class="mt-3 line-clamp-3 text-base">
                {{ $product->description }}
            </div>
            <div class="flex items-center text-sm text-gray-400 font-semibold space-x-2 mt-5">
                <div><span class="">{{ $product->ideas_count }}</span> <span class="font-normal">{{ Str::plural(__('text.idea'), $product->ideas_count) }}</span></div>
                <div>&bull;</div>
                <div><span class="">{{ $product->categories_count }}</span> <span class="font-normal">{{ Str::plural(__('text.category'), $product->categories_count) }}</span></div>
            </div>
        </div>
    </div>
</div>
