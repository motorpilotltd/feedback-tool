@props([
    'eventToOpenModal' => null,
    'livewireEventToOpenModal' => null,
    'eventToCloseModal',
    'modalTitle',
    'modalDescription',
    'modalConfirmButtonText',
    'wireClick'
])
<div
    x-data="{ isOpen: false }"
    x-show="isOpen"
    @keydown.escape.window="isOpen = false"
    @if ($eventToOpenModal)
        {{ '@' . $eventToOpenModal }}.window="
            isOpen = true
            $nextTick(() => $refs.confirmButton.focus())
        "
    @endif

    x-init="
        $wire.on('{{ $eventToCloseModal }}', () => {
            isOpen = false
        })

        @if ($livewireEventToOpenModal)
            $wire.on('{{ $livewireEventToOpenModal }}', () => {
                isOpen = true
                $nextTick(() => $refs.confirmButton.focus())
            })
        @endif
    "
    class="fixed z-20 inset-0 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-cloak
>
    <div
        x-show.transition.opacity.duration.300ms="isOpen"
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
    >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            x-show.transition.opacity.duration.300ms="isOpen"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <!-- Heroicon name: outline/exclamation-triangle -->
                    <svg class="h-6 w-6 text-red-base" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>

                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        {{ $modalTitle }}
                    </h3>
                    <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        {{ $modalDescription }}
                    </p>
                    </div>
                </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button wire:click="{{ $wireClick }}" x-ref="confirmButton" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-base text-base font-medium text-white hover:bg-blue-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-base sm:ml-3 sm:w-auto sm:text-sm">
                    {{ $modalConfirmButtonText }}
                </button>
                <button
                    @click="isOpen = false"
                    type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-base sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                Cancel
                </button>
            </div>
        </div>
    </div>
</div>
