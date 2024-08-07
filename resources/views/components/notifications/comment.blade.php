@props([
    'notification',
])

<a
    href="{{ route('idea.show', $notification->data['idea_slug']) }}"
    @click.prevent="
        isOpen = false
    "
    wire:click.prevent="markAsRead('{{ $notification->id }}')"
    href="#"
    class="flex hover:bg-gray-100 transition duration-150 ease-in px-5 py-3"
>
    <div class="flex-shrink-0 items-center mt-1">
        <x-user-avatar sm :name="$notification->data['user_name']" :avatar="$notification->data['user_avatar']" />
    </div>
    <div class="ml-4">
        <div class="line-clamp-4">
            <span class="font-semibold">{{ $notification->data['user_name'] }}</span>
            commented on
            <span class="font-semibold">{{ $notification->data['idea_title'] }}</span>:
            <span class="italic">"{{ Str::limit($notification->data['comment_content'], 35, '...') }}"</span>
        </div>
        <div class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</div>
    </div>
</a>
