@php
    $hasMarkedSpam = isset($authUser->id) ? $comment->spams->pluck('id')->contains($authUser->id) : false;
@endphp
<div
    id="comment-{{$comment->id}}"
    class="
        @if (!empty($comment->is_status_update)) is-status-update comment-status-{{ Str::kebab($comment->is_status_update) }} @endif
        {{ $isPinned ? 'pinned-comment border border-blue-300': '' }}
        comment-container
        relative
        bg-white
        rounded-xl
        flex
        flex-col
        transition
        duration-500
        ease-in
        mt-4"
>
    @if ($isPinned)
        {{-- Workaround for scrollintoview offsetting the fixed nav --}}
        <div class="pinned-scroll-comment" style="height: 50px; position: absolute; top: -150px; pointer-events: none;"></div>
    @endif
    <div class="flex flex-col md:flex-row flex-1 px-4 py-6">
        <div class="flex-none">
            <a href="#">
                <x-user-avatar
                    :user="$comment->user"
                />
            </a>
            @if ($comment->user && $comment->user->can('manage', $parentIdea))
                <div class="text-center uppercase text-blue-base text-xxs font-bold mt-1">Admin</div>
            @endif

        </div>
        <div class="md:mx-4 w-full">
            <div class="text-gray-600">
                @can('manage', $parentIdea)
                    @if (isset($comment->spams_count) && $comment->spams_count > 0)
                        <div class="text-red-base mb-2">Spam Reports: {{ $comment->spams_count }}</div>
                    @endif
                @endcan
                @if (!empty($comment->is_status_update))
                    <h4 class="text-xl font-semibold mb-3">
                        Status changed to "{{ $comment->status->name }}"
                    </h4>
                @endif
                <div>
                    {!! nl2br(e($comment->content)) !!}
                </div>
            </div>

            <livewire:attachment.attach-file-preview :key="$comment->id" :model="$comment" :hasAuthCheck="true"/>

            <div class="flex items-center justify-between mt-6">
                <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
                    <div class="@if (!empty($comment->is_status_update)) text-blue-base @endif font-bold">
                        {!!
                            __('general.user_name_link', [
                                'name' => $comment->user->name ?? __('text.userdeleted'),
                                'link' => route('user.viewprofile', ['user' => $comment->user->id])
                            ])
                        !!}
                    </div>
                    <div>&bull;</div>
                    <div>{{ $comment->created_at->diffForHumans() }}</div>
                    @if ($comment->user && $comment->user->id === $parentIdea->author->id)
                        <div>&bull;</div>
                        <div class="rounded-full border bg-gray-100 px-3 py-1">OP</div>

                    @endif

                    @if ($isPinned || $pinnedCommentId == $comment->id)
                        <div>&bull;</div>
                        <x-button xs rounded label="Pinned comment" class="cursor-default" />
                    @endif


                </div>
                @auth
                    <div
                        class="flex items-center space-x-2"
                    >
                        <x-dropdown>
                            <x-slot name="trigger">
                                <x-button rounded xs>
                                    <x-icon name="ellipsis-horizontal" class="w-5 h-5" />
                                </x-button>
                            </x-slot>
                            @can('update', $comment)
                                <x-dropdown.item
                                    label="{{ __('text.editcomment') }}"
                                    wire:click="$dispatchTo('modal.edit-comment', 'setEditComment', { id: {{ $comment->id }} })"
                                />
                            @endcan
                            @can('delete', $comment)
                                <x-dropdown.item
                                    label="{{ __('text.deletecomment') }}"
                                    wire:click="deleteConfirm({{ $comment->id }})"
                                />
                            @endcan
                            <x-dropdown.item
                                label="{{ $hasMarkedSpam ? __('text.unmarkspam') :  __('text.markasspam') }}"
                                wire:click="markSpamConfirm({{ $comment->id }}, {{ (int) $hasMarkedSpam }})"
                            />
                            @if ($pinnedCommentId !== $comment->id)
                                @can('pinIdeaComment', $parentIdea)
                                    <x-dropdown.item
                                        label="{{ $isPinned ? __('text.unpinthiscomment') :  __('text.pinthiscomment') }}"
                                        wire:click.prevent="pinningComment('{{ $comment->id }}', '{{ $isPinned }}')"
                                    />
                                @endcan
                            @endif
                            @can('manage', $parentIdea)
                                @if (isset($comment->spams_count) && $comment->spams_count > 0)
                                    <x-dropdown.item
                                        label="{{ __('text.notaspam') }}"
                                        wire:click="commentNotSpamConfirm({{ $comment->id }})"
                                    />
                                @endif
                            @endcan
                        </x-dropdown>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div> <!-- end comment-container -->
