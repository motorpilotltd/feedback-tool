<div
    x-cloak
    x-data="{ isOpen: false }"
    x-show="isOpen"
    @keydown.escape.window="
        isOpen = false
        Livewire.dispatch('editCommentModalClosed')
    "
    @open-edit-comment-modal.window="
        isOpen = true
        $dispatch('custom-focus-comment-content')
    "
    x-init="
        $wire.on('closeCommentFormModal', () => {
            isOpen = false
        })
    "
    class="fixed z-10 inset-0 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <div class="flex items-end justify-center min-h-screen pt-8 px-4 pb-20 text-center sm:block sm:p-0">
        <div
            x-show.transition.opacity="isOpen"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            aria-hidden="true"
        ></div>
        <div
        x-show.transition.origin.top.duration.300ms="isOpen"
        class="modal inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-32 sm:align-middle sm:max-w-lg sm:w-full"
        >
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button
                    @click="
                        isOpen = false
                        Livewire.dispatch('editCommentModalClosed')
                    "

                    class="text-gray-400 hover:text-gray-500"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                </button>
            </div>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-center text-lg font-medium text-gray-900">{{ __('text.updatingcomment') }}</h3>
                <livewire:forms.comment-form wire:key='$commentId' :idea='$idea->id' :action='$action'>
            </div>

        </div>
    </div>
</div>
