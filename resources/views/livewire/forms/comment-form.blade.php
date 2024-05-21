<div
    x-data=""
    class="comment-form w-full"
    @custom-focus-comment-content.window="
        $nextTick(() => {
            setTimeout(() => {
                $refs.comment.focus()
            }, 300);
        })
    "
>
    @if ($isRender)
        <form wire:submit="{{ $wireSubmit }}" action="#" class="space-y-4 px-4 py-6">
            <div>
                <textarea x-ref="comment" wire:model="content" name="post_comment" cols="30" rows="4" class="w-full text-sm bg-gray-100 rounded-xl placeholder-gray-400 border-none px-4 py-2" placeholder="{{ __('general.typeinyourcomments') }}"></textarea>
                @error('content')
                    <x-input.error>{{ $message }}</x-input.error>
                @enderror
            </div>

            @if (!empty($comment))
                <livewire:attachment.attach-file-preview :key="$comment->id ? $comment->id . time() : time()" :model="$comment"  :hasdelete="true" />
            @endif

            <x-input.filepond
                wire:model.live="attachments"
                multiple
                allowFileTypeValidation
                acceptedFileTypes="{{ $allowedTypes }}"
                allowFileSizeValidation
                maxFileSize="{{ $allowedSize }}"
                maxFiles="{{ $maxFiles }}" />

            @error('attachments')
                <x-input.error>{{ $message }}</x-input.error>
            @enderror
            <div class="flex flex-col md:flex-row items-center md:space-x-3 justify-end">
                <x-button info  type="submit" label="{{ __('text.commentbutton:' . $action) }}" />
            </div>
        </form>
    @else
        <div class="mx-auto mt-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-400 mx-auto h-40 w-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-gray-300 text-center font-bold mt-4 mb-6">
                <input type="hidden"  x-ref="comment">
                {{ __('error.actionnotpermitted') }}
            </div>
        </div>
    @endif
</div>
