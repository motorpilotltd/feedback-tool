@props([
    'link' => '#',
    'label' => 'Link',
    'icon' => 'link',
    'logo' => null,
    'logoInitials' => null,
    'labelLimit' => 0,
])
<div class="flex">
    <a href="{{ $link }}" target="_blank" class="group inline-flex space-x-2 truncate text-sm leading-5 items-center">
        @if ($logo)
            <x-avatar sm squared src="{{ $logo }}" />
        @endif

        @if ($logoInitials)
        <x-avatar sm squared label="{{ $logoInitials }}" />
    @endif

        <p class="truncte group-hover:text-blue-hover transition ease-in-out duration-150 text-blue-base">
            {{ $labelLimit ? Str::limit($label, $labelLimit, '...') : $limit }}
        </p>
    </a>
</div>
