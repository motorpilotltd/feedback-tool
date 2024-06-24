@props([
    'product'
])

<div
    x-data
    @click="const clicked = $event.target
        const target = clicked.tagName.toLowerCase()
        const ignores = ['button', 'svg', 'path', 'a']
        if (! ignores.includes(target)) {
            clicked.closest('.product-container').querySelector('.product-link').click()
        }
    "
    class="product-container group bg-white border rounded-lg hover:shadow-md hover:border-blue-200 p-4 flex cursor-pointer"
>
    <!-- Logo/Photo (1/4 size of the thumbnail) -->
    <div class="w-1/4">
        <!-- You can insert your logo or photo here -->
        @if ($image = $product->getMedia('attachments')->first())
            <x-avatar xl squared src="{{ route('file.attachments.show', ['display', $image->file_name]) }}" />
        @else
            <x-avatar xl squared label="{{ Str::substr($product->name, 0, 2) }}" />
        @endif
    </div>

    <!-- Thumbnail Content (3/4 size of the thumbnail) -->
    <div class="w-3/4 flex flex-col justify-between">
        <div class="flex flex-col space-y-2">
            <a href="{{ route('product.show', $product) }}" class="product-link font-bold group-hover:text-blue-hover">{{ $product->name }}</a>
            @if ($product->description)
                <small>{{ Str::limit($product->description, 55, '...')  }}</small>
            @else
                <small class="text-gray-400 italic">No description</small>
            @endif
        </div>

        <div class="flex mt-4 flex-row justify-between">
            <small>Categories: <b>{{ $product->categories_count }}</b></small>
            <small>Ideas: <b>{{ $product->ideas_count }}</b></small>
        </div>
    </div>
</div>
