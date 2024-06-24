<div
    {{-- wire:poll.3000ms.visible="getNotificationCount" --}}
    x-data="{ isOpen: false }"
    class="relative mt-1 pr-2"
>
    <button
        @click="
            isOpen = !isOpen
            if (isOpen) {
                Livewire.dispatch('getNotifications');
            }
        "
    >
        <svg class="h-8 w-8 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
        </svg>
        @if ($notificationCount)
            <div class="absolute rounded-full bg-red-base text-white text-xxs w-6 h-6 flex justify-center items-center border-2 -top-1 -right-0.5" >
                {{ $notificationCount }}
            </div>
        @endif

    </button>
    <ul
        class="absolute w-76 max-h-128 overflow-y-auto text-gray-700 md:w-96 text-left bg-white shadow-dialog text-sm rounded-xl z-10 -right-28 md:-right-12"
        x-show.transition.origin.top="isOpen"
        @click.away="isOpen = false"
        @keydown.escape.window="isOpen = false"
        x-cloak
    >
        @if ($notifications->isNotEmpty() && !$isLoading)
            @foreach ($notifications as $notification)
                <li>
                    @switch($notification->type)
                        @case('comment')
                            <x-notifications.comment
                                :notification="$notification"
                            />
                            @break
                        @case('idea')
                            <x-notifications.idea
                                :notification="$notification"
                            />
                            @break
                    @endswitch
                </li>
            @endforeach
            <li class="border-t border-gray-300 text-center">
                <button
                    wire:click="markAllAsRead"
                    @click="isOpen = false";
                    class="w-full block font-semibold hover:bg-gray-100 transition duration-150 ease-in px-5 py-4"
                >Mark all as read</button>
            </li>
        @elseif ($isLoading)
            @foreach (range(1,3) as $item)
                <li class="flex items-center transition duration-150 ease-in px-5 py-4 animate-pulse">
                    <div class="bg-gray-200 rounded-xl w-10 h-10"></div>
                    <div class="flex-1 ml-4 space-y-2">
                        <div class="bg-gray-200 w-full rounded h-4"></div>
                        <div class="bg-gray-200 w-full rounded h-4"></div>
                        <div class="bg-gray-200 w-1/2 rounded h-4"></div>
                    </div>
                </li>
            @endforeach
        @else
            <li class="mx-auto w-40 py-6">
                {{-- <img src="{{asset('img/no-ideas.svg')}}" alt="No ideas" class="mx-auto mix-blend-luminosity"> --}}
                <x-icon name="exclamation-circle" class="w-8 h-8 mx-auto text-gray-400" />
                <div class="text-gray-400 text-center font-bold mt-4">
                    No new notifications..
                </div>
            </li>
        @endif
    </ul>
</div>
