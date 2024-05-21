<div id="show-links-dropdown">
    @if ($links->isNotEmpty())
        <x-dropdown>
            <x-slot name="trigger">
                <x-button :label="$title" blue />
            </x-slot>
            @foreach ($links as $key => $link)
                <x-dropdown.item :href="$link['url']" :separator="($key !== 0)" :label="$link['label']" />
            @endforeach
        </x-dropdown>
    @endif
</div>
