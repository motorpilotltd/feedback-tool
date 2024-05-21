<div class="attachment-preview flex flex-col mt-8">
    @php
        $arrAttachments = $attachments->toArray();
    @endphp
    @if (!empty($arrAttachments))
        <div class="flex flex-wrap my-4 p-2 border border-gray-200 rounded-lg relative">
            <h2 class="w-full absolute -top-3">
                <span class="bg-white text-xs px-2 text-gray-400">{{ Str::plural(__('text.attachment'), count($arrAttachments)) }}</span>
            </h2>
            @foreach ($attachments as $file)
                @php
                    $checkAuth = $hasAuthCheck && !auth()->guest();
                @endphp
                <div class="relative w-1/4 h-28 p-1 transition duration-150 ease-in">
                    @if ($checkAuth || $hasdelete)
                        <img class="object-cover h-full w-full" src="{{ route('file.attachments.show', ['display', $file->file_name]) }}" alt="">
                    @endif

                    @if($hasdelete)
                        <div class="absolute rounded-full bg-red-base text-xxs w-6 h-6 flex justify-center items-center top-0 right-0">
                            <button
                                wire:click.prevent='deleteFile({{ $loop->index }})'
                                class="text-white"
                                type="button"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                    @else

                        <div @if ($checkAuth) wire:click="$dispatchTo('modal.view-image', 'setImageUrl', {imageUrl: '{{ route('file.attachments.show', ['display', $file->file_name]) }}'})" @endif
                            class="m-1 @if($checkAuth) opacity-0 hover:opacity-100 @else opacity-100 @endif  duration-300 absolute inset-0 z-9 flex justify-center items-center font-semibold bg-gray-400 bg-opacity-70 cursor-pointer"
                        >
                            @if ($checkAuth)
                                <x-icon.eye />
                            @else
                                <div x-data="{ hover: false }" class="relative w-full">
                                    <div x-on:mouseover="hover = true" x-on:mouseout="hover = false" class="w-full">
                                        <x-icon.eye-off class="mx-auto" />
                                    </div>
                                    <span x-cloak x-show="hover" class="absolute -top-12 text-xs text-white bg-gray-800 rounded-lg p-1.5 z-10">{{ __('text.logintoview') }}</span>
                                </div>
                            @endif

                        </div>
                    @endif
                </div>

            @endforeach
        </div>
    @endif
</div>
