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
    <img src="{{ $notification->data['user_avatar'] }}" class="rounded-xl w-10 h-10" alt="avatar">
    <div class="ml-4">
        <div class="line-clamp-4">
            <span class="font-semibold">{{ $notification->data['user_name'] }}</span>
            @if (isset($notification->data['on_behalf']) && $notification->data['on_behalf'])
                added an idea on your behalf and has set you as the <i>author</i>...
                <br/>
            @else
                suggested a new idea at
                <span class="font-semibold">{{ Str::limit($notification->data['product_title'], 35, '...') }}</span>
                titled as
            @endif
            <span class="italic font-semibold">"{{ Str::limit($notification->data['idea_title'], 35, '...') }}"</span>
        </div>
        <div class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</div>
    </div>
</a>
