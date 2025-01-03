<div class="space-y-4">
    @if ($links)
        @foreach ($links as $index => $link)
            <div :key="{{ 'links_'. $index }}" class="relative p-2 bg-gray-100 rounded shadow">
                <x-mini-button
                    rounded
                    wire:click="removeLinkFields({{ $index }})"
                    xs
                    negative
                    icon="trash"
                    class="absolute -top-2 -right-2 mb-2"
                />
                <x-input
                    wire:model.live.debounce.500ms="links.{{ $index }}.label"
                    id="links_{{ $index }}_label"
                    name="links_{{ $index }}_label"
                    label="Title"
                    placeholder="Link's title..."
                    :error="$errors->first('links.'.$index.'.label')"
                    wire:key="links.{{ $index }}.label"
                />
                @error('links.'.$index.'.label')
                    <x-input.error>{{ $message }}</x-input.error>
                @enderror
                <x-input
                    wire:model.live.debounce.500ms="links.{{ $index }}.url"
                    id="links_{{ $index }}_url"
                    name="links_{{ $index }}_url"
                    label="URL"
                    placeholder="https://example.com/link"
                    :error="$errors->first('links.'.$index.'.url')"
                    wire:key="links.{{ $index }}.url"
                />
                @error('links.'.$index.'.url')
                    <x-input.error>{{ $message }}</x-input.error>
                @enderror
            </div>
        @endforeach
    @endif

    <x-button
        wire:click="addLinkFields"
        icon="plus"
        primary
        label="Add Link"
    />
</div>
