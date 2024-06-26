<div x-data="{tags: ['what', 'is', 'up'], newTag: '', inputName: 'foo' }" class="bg-grey-lighter px-8 py-16 min-h-screen">
    <template x-for="tag in tags">
        <input type="hidden" x-bind:name="inputName + '[]'" x-bind:value="tag">
    </template>

    <div class="max-w-sm w-full mx-auto">
        <div class="tags-input">
            <template x-for="tag in tags" :key="tag">
                <span class="tags-input-tag">
                    <span x-text="tag"></span>
                    <button type="button" class="tags-input-remove" @click="tags = tags.filter(i => i !== tag)">
                        &times;
                    </button>
                </span>
            </template>

            <input class="tags-input-text" placeholder="Add tag..."
                @keydown.enter.prevent="if (newTag.trim() !== '') tags.push(newTag.trim()); newTag = ''"
                x-model="newTag"
            >
        </div>
    </div>
</div>

@push('styles')
    <style>
        .tags-input {
            display: flex;
            flex-wrap: wrap;
            background-color: #fff;
            border-width: 1px;
            border-radius: .25rem;
            padding-left: .5rem;
            padding-right: 1rem;
            padding-top: .5rem;
            padding-bottom: .25rem;
        }

        .tags-input-tag {
            display: inline-flex;
            line-height: 1;
            align-items: center;
            font-size: .875rem;
            background-color: #bcdefa;
            color: #1c3d5a;
            border-radius: .25rem;
            user-select: none;
            padding: .25rem;
            margin-right: .5rem;
            margin-bottom: .25rem;
        }

        .tags-input-tag:last-of-type {
            margin-right: 0;
        }

        .tags-input-remove {
            color: #2779bd;
            font-size: 1.125rem;
            line-height: 1;
        }

        .tags-input-remove:first-child {
            margin-right: .25rem;
        }

        .tags-input-remove:last-child {
            margin-left: .25rem;
        }

        .tags-input-remove:focus {
            outline: 0;
        }

        .tags-input-text {
            flex: 1;
            outline: 0;
            padding-top: .25rem;
            padding-bottom: .25rem;
            margin-left: .5rem;
            margin-bottom: .25rem;
            min-width: 10rem;
        }

        .py-16 {
            padding-top: 4rem;
            padding-bottom: 4rem;
        }
    </style>
@endpush
