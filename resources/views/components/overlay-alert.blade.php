@props([
    'redirect' => false,
    'messageToDisplay' => '',
    'type' => 'success'
])

<div
    x-data="{
        isOpen: false,
        messageToDisplay: '{{ $messageToDisplay }}',
        type: '{{ $type }}',
        showNotification(message, type) {
            this.type = type
            this.isOpen = true
            this.messageToDisplay = message
            setTimeout(() => {
                this.isOpen = false
            }, 10000)
        }
    }"
    @dispatchnotify.window="showNotification($event.detail.message, $event.detail.type)"
    x-cloak
    x-show="isOpen"
    @if ($redirect)
        x-init="
            $nextTick(() => showNotification(messageToDisplay, type))
        "
    @endif
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-8"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-8"
    @keydown.escape.window="isOpen = false"
    class="flex z-20 justify-between max-w-sm sm:max-w-md w-full fixed top-16 right-0 bg-white rounded-xl shadow-lg border px-4 py-5 mx-2 sm:mx-6 my-8"
>
    <div class="flex items-center">
        <template x-if="type == 'success'">
            <x-icon name="check-circle" class="w-6 h-6 text-green-600 shrink-0" />
        </template>
        <template x-if="type == 'delete'">
            <x-icon name="trash" class="w-6 h-6 text-green-600 shrink-0" />
        </template>
        <template x-if="type == 'warning'">
            <x-icon name="exclamation-triangle" class="w-6 h-6 text-red-base shrink-0" />
        </template>
        <div
            class='ml-2 text-gray-500 text-sm sm:text-base'
            x-html="messageToDisplay"
        >
        </div>
    </div>
    <button @click="isOpen = false" class="text-gray-400 hover:text-gray-500">
        <x-icon name="x-mark" class="w-6 h-6 text-gray-500 shrink-0" />
    </button>
</div>
