@props([
    'for' => null
])
<label for="{{ $for }}" class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
    {{ $slot }}
</label>
