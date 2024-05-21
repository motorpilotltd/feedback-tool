<div class="tags-list">
    @if ($tagsGroup->isNotEmpty())
        <div class="flex flex-col mt-2 border-t-2 border-gray-100">
            <h1 class="text-lg font-medium">Tags:</h1>
            @foreach ($tagsGroup as $tg)
                @if ($tg->tags->isNotEmpty())
                    <div class="flex flex-col mt-2">
                        <div class="text-sm font-medium">
                            {{ $tg->name }}
                        </div>
                        <div class="flex flex-row flex-wrap gap-2">
                            @foreach ($tg->tags as $tag)
                                @if ($tag->ideas_count > 0)
                                    <x-button
                                        href="{{ route('product.tag', [$product, $tag]) }}"
                                        2xs
                                        rounded
                                        color="{{ ($currentTagId === $tag->id) ? 'blue' : 'slate' }}"
                                        label="{{ $tag->name . '(' . $tag->ideas_count . ')' }}"
                                    />
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
