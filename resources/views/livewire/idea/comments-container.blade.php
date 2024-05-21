<div
    wire:poll.7s.visible
>
    @if ($this->comments->isNotEmpty())
        <div class="comments-container relative space-y-6 md:ml-22 pt-4 my-8 mt-1">
            @foreach ($this->comments as $comment)
                <livewire:idea.comment
                    :key="$comment->id"
                    :comment="$comment->id"
                    :parentIdea="$idea->id"
                />
            @endforeach
        </div>
        <div class="my-8 ml-22">
            {{ $this->comments->links() }}
        </div>
    @else
        <x-no-items-available :items="__('comments')" />
    @endif
</div>
