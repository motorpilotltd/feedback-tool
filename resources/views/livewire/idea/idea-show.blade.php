@php
// Note: A work around to refresh spam-related properties
$spamCount = $idea->spams()->count();

@endphp
<div class="idea-show container">
    <div class="idea-container bg-white rounded-xl flex mt-4">
        <div class="flex flex-col md:flex-row flex-1 px-4 py-6">
            <div class="flex-none mx-2">
                <a href="#">
                    <x-user-avatar
                        :user="$idea->author"
                    />
                </a>
            </div>
            <div class="w-full mx-2 md:mx-4">
                <h4 class="text-xl font-semibold mt-2 md:mt-0">
                    {{ $idea->title }}
                </h4>
                <span class="text-gray-400 text-xs">
                    <x-added-by
                        :name="$idea->author->name ?? null"
                        :date="$idea->created_at->diffForHumans()"
                        asLink="{{ route('user.viewprofile', ['user' => $idea->author->id]) }}"
                    />
                </span>
                <div class="text-gray-600 mt-3">
                    @can('manage', $idea)
                        @if ($spamCount > 0)
                            <div class="text-red-base mb-2">{{ __('text.spamreportcount', ['count' => $spamCount]) }}</div>
                        @endif
                    @endcan
                    {!! nl2br(e($idea->content)) !!}
                </div>

                <livewire:attachment.attach-file-preview :key="$idea->id" :model="$idea" :hasAuthCheck="true"/>
                @if ($ideaTagsByGroup->isNotEmpty() && $hasTags)
                    <div class="flex flex-col">
                        <h1 class="text-base font-medium">Tags:</h1>
                        @foreach ($ideaTagsByGroup as $tg)
                            @if ($tg->tags->isNotEmpty())
                                <div class="flex flex-col mt-4">
                                    <div class="text-sm font-medium">
                                        {{ $tg->name }}
                                    </div>
                                    <div class="flex flex-row flex-wrap gap-2">
                                        @foreach ($tg->tags as $tag)
                                            <x-button
                                                href="{{ route('product.tag', [$product, $tag]) }}"
                                                xs
                                                rounded
                                                slate
                                            >{{ $tag->name }}</x-button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="flex flex-col md:flex-row md:items-center justify-between mt-6">
                    <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
                        <div>{{ Str::limit($idea->category->name, 35, '...')  }}</div>
                        <div>&bull;</div>
                        <div class="text-gray-900">{{ $idea->comments()->count() }} comments</div>
                    </div>
                    <div
                        class="flex items-center space-x-2 mt-4 md:mt-0"
                    >
                        <x-idea-status-badge :status="$idea->ideaStatus" />
                        @auth
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <x-button rounded xs>
                                        <x-icon name="ellipsis-horizontal" class="w-5 h-5" />
                                    </x-button>
                                </x-slot>
                                @can('update', $idea)
                                    <x-dropdown.item
                                        x-data="{ editUrl: '{{ route('idea.edit', $idea) }}' }"
                                        @click="window.location.href = editUrl"
                                    >
                                        {{ __('text.editidea') }}
                                    </x-dropdown.item>
                                @endcan
                                @can('delete', $idea)
                                    <x-dropdown.item
                                        label="{{ __('text.deleteidea') }}"
                                        wire:click="deleteConfirm"
                                    />
                                @endcan
                                <x-dropdown.item
                                     wire:click="ideaSpamConfirm({{$hasMarkedSpam}})"
                                    label="{{ $hasMarkedSpam ? __('text.unmarkspam') : __('text.markasspam') }}"
                                />
                                @can('manage', $idea)
                                    @if ($spamCount > 0)
                                        <x-dropdown.item
                                            wire:click="ideaNotSpam"
                                            label="{{ __('text.notaspam') }}"
                                        />
                                    @endif
                                @endcan
                            </x-dropdown>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end idea-container -->
    @if ($idea->pinnedComment)
        <div id="pinned-comment">
            <livewire:idea.comment
                :key="$idea->pinnedComment->id . '-pinned'"
                :comment="$idea->pinnedComment->id"
                :parentIdea="$idea->id"
                :isPinned="true"
            />
        </div>
    @endif
    <div class="buttons-container flex flex-col">
        <div class="flex flex-col md:flex-row items-center justify-between space-x-4 my-4">

            @can('manage', $idea)
                <livewire:idea.set-status :idea="$idea->id" />
            @endcan
            {{-- TODO: Causing uncaught promise --}}
            <livewire:idea.votes-count-button
                :key="'vote-horizontal'"
                :ideaId='$idea->id'
                :hasVoted='$hasVoted'
                :votesCount='$votesCount'
                :isHorizontal=true
            />
        </div>
        <livewire:idea.add-comment :idea="$idea->id"/>
    </div> <!-- end buttons-container -->
</div>
