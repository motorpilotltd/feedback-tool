<div class="min-h-full"
    wire:init='loaded'
>
    <livewire:idea.search-bar />
    @if (!empty($search))
        <div class="flex items-center justify-between">
            @if ($ideas->count())
                <div class="relative rounded-lg vote-existing bg-blue-base text-lg text-white px-2 py-2">{{ __('general.vote_from_existing') }}</div>
                <div class="text-2xl">{{ __('text.or') }}</div>
            @else
                <div></div>
                <div>
                    <x-no-items-available :items="__('ideas')" />
                </div>
            @endif
            @auth
                <button
                    x-data=""
                    wire:click="suggestingIdea('{{ e($searchTitle) }}')"
                    class="rounded-lg bg-blue-base text-lg text-white px-2 py-2 hover:bg-blue-hover hover:shadow-card"
                >
                        {{ __('general.post_new_idea') }}
                </button>
            @endauth
        </div>
    @else
        <livewire:idea.filters
            :product="$product"
            :categories="$categories"
        />
    @endif
    <div  wire:key="ideacontainer" class="idea-cards-container space-y-6 my-6" wire:loading.class="hidden">
        @if ($ideas->isNotEmpty())
            @foreach ($ideas as $idea)
                <livewire:idea.card
                    :key="$idea->getForLivewireKey()"
                    :idea="$idea"
                    :votesCount="$idea->votes_count"
                    :searchKeywords="$search"
                />
            @endforeach
        @endif
    </div>


    <div  wire:key="skeletonloader" class="idea-cards-container space-y-6 my-6 hidden" wire:loading.class.remove="hidden">
        <x-skeleton-loader />
    </div>

    <div class="my-8">
        {{ $ideas->links() }}
    </div>
</div>
