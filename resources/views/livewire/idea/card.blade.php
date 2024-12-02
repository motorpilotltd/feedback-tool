<div
    x-data
    @click="const clicked = $event.target
        const target = clicked.tagName.toLowerCase()
        const ignores = ['button', 'svg', 'path', 'a']
        if (! ignores.includes(target)) {
            clicked.closest('.idea-card').querySelector('.idea-link').click()
        }
    "
    class="idea-card group @if (!$isViewOnly) duration-150 rounded-xl @else border rounded-md @endif transition ease-in bg-white flex cursor-pointer hover:shadow-md "
>
    @if (!$isViewOnly)
        <livewire:idea.votes-count-button
            :key="'vote-horizontal-'.$idea->id"
            :ideaId="$idea->id"
            :hasVoted="$hasVoted"
            :votesCount="$idea->votes_count"
        />

        <div class="flex flex-col md:flex-row flex-1 px-2 py-6">
            <div class="flex-none mx-2 md:mx-0">
                <a href="#">
                    <x-user-avatar
                        :user="$idea->author"
                    />
                </a>
            </div>
            <div class="w-full flex flex-col justify-between mx-2 md:mx-4">
                <h4 class="text-xl font-semibold mt-2 md:mt-0">
                    <a href="{{ $idea->idea_link }}" class="idea-link group-hover:text-blue-hover">
                        @if ($searchKeywords)
                            {!! highlightMatchedSearch($idea->title, $searchKeywords) !!}
                            {{-- to include bg-yellow-200 in the tailwind generation --}}
                            <span class="bg-yellow-200 hidden"></span>
                        @else
                            {{ $idea->title }}
                        @endif
                    </a>
                </h4>
                <span class="text-gray-400 text-xs"><x-added-by :name="$idea->author->name ?? null" :date="$idea->created_at->toDayDateTimeString()" /></b></span>
                @if ($idea->created_at != $idea->updated_at)
                    <span class="text-gray-400 text-xs">{{ __('text.lastupdated', ['time' => $idea->updated_at->toDayDateTimeString()]) }}</span>
                @endif
                <div class="text-gray-600 mt-3 line-clamp-3">
                    @can('manage', $idea)
                        @if (isset($idea->spams_count) && $idea->spams_count > 0)
                            <div class="text-red-base mb-2">{{ __('text.spamreportcount', ['count' => $idea->spams_count]) }}</div>
                        @endif
                    @endcan
                    {{ $idea->content }}
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between mt-6">
                    <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2 w-full">
                        <div>
                            {{ Str::limit($idea->category->name, 35, '...')  }}
                        </div>
                        <div>&bull;</div>
                        <div wire:ignore class="text-gray-900">{{ Str::plural(__('general.commentcount', ['count' => $idea->comments_count]),  $idea->comments_count) }}</div>
                    </div>
                    <div
                        x-data="{ isOpen: false }"
                        class="flex items-center space-x-2 mt-4 md:mt-0 w-full justify-end"
                    >
                        @if ($idea->ideaStatus)
                            <x-idea-status-badge :status="$idea->ideaStatus" />
                        @endif
                    </div>

                    <livewire:idea.votes-count-button
                        :key="'vote-vertical-'.$idea->id"
                        :ideaId="$idea->id"
                        :hasVoted="$hasVoted"
                        :votesCount="$idea->votes_count"
                        :isResponsive="true"
                        :isHorizontal="false"
                    />
                </div>
            </div>
        </div>
    @else
    <div class="flex flex-col p-2 w-full">
        <div class="w-full flex flex-col justify-between">
            <h5 class="text-md font-semibold mt-2 md:mt-0 text-blue-500">
                <a href="{{ $idea->idea_link }}" class="idea-link flex flex-row" target="_blank">
                    {{ $idea->title }}
                    <x-icon name="arrow-top-right-on-square" class="w-4 h-4 ml-2 hidden group-hover:block" />
                </a>
            </h5>
        </div>
        <div class="flex flex-col md:flex-row md:items-center justify-between mt-1">
            <div class="flex items-center text-xs text-gray-400 font-semibold space-x-1 w-full">
                <div wire:ignore class="text-gray-900">{{ Str::plural(__('general.commentcount', ['count' => $idea->comments_count]),  $idea->comments_count) }}</div>
                <div>&bull;</div>
                <div wire:ignore class="text-gray-900">{{ Str::plural(__('general.votecount', ['count' => $idea->votes_count]),  $idea->votes_count) }}</div>
                <div>&bull;</div>
                <div>{{ $idea->created_at->toDayDateTimeString() }}</div>
            </div>
            <div
                x-data="{ isOpen: false }"
                class="flex items-center space-x-2 mt-4 md:mt-0 w-full justify-end"
            >
                @if ($idea->ideaStatus)
                    <x-idea-status-badge :status="$idea->ideaStatus" />
                @endif
            </div>
        </div>
    </div>
    @endif

</div>
