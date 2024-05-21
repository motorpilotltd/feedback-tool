<div
    class="side-bar-category-links mt-2"
>
    <ul class="">
        @forelse ( $categories as $category )
            @php
                $catLink = route('category.show', $category);
            @endphp

            @if(request()->url() == $catLink)
                <li class="text-base mb-2 text-blue-base group">
                    <span
                        class="flex flex-row"
                    >
                        {{ $category->name }}
                        <div
                            class="mx-2 h-6 px-4 rounded-xl border-blue-400 border text-blue-400 e text-xs font-semibold w-4 flex justify-center items-center"
                        >
                            {{ $category->ideas_count }}
                        </div>
                    </span>
                </li>
            @else
                <li class="text-base mb-2 hover:text-blue-base group">
                    <a
                        class="flex flex-row"
                        href="{{ $catLink }}"
                    >
                        {{ $category->name }}
                        <div
                            class="mx-2 h-6 px-4 rounded-xl border border-gray-400 text-gray-400 text-xs font-semibold w-4 flex justify-center items-center"
                        >
                            {{ $category->ideas_count }}
                        </div>
                    </a>
                </li>
            @endif
        @empty
            <i>{{ __('text.noavailablecategories') }}</i>
        @endforelse
    </ul>
</div>
