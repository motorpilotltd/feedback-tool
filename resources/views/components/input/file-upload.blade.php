<div class='w-1/2'>
    <label for="{{ $attributes['id'] }}"  class="inline-flex items-center justify-center w-full h-11 text-xs bg-gray-200 font-semibold rounded-xl border border-gray-200 hover:border-gray-400 transition ease-in duration-150 px-6 py-3">
        <svg class="text-gray-600 w-4 transform -rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
        </svg>
        <span class="ml-1">{{ __('text.attach') }}</span>
        <input type='file' class="hidden" {{ $attributes }}/>
    </label>
</div>
