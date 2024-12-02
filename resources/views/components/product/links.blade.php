@props([
    'links' => [],
])
<div id="product-links">
    @if (!empty($links))
        <div class="flex flex-col border-t-2 py-2 border-gray-100">
            <h1 class="text-lg font-medium">Links</h1>
            <ul>
                @foreach ($links as $item)
                    <li class="text-base mb-2 text-blue-base group">
                        <a
                            class="flex flex-row align-middle"
                            href="{{ $item['url'] }}"
                            target="_blank"
                        >
                        <span><x-icon name="arrow-top-right-on-square" class="w-4 h-4 mr-1 mt-1" /></span>
                        <span class="">{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
