@props([
    'item' => [],
    'logo' => false,
    'logoIntials' => null,
    'logoSrc' => null
])

<div class="flex flex-col md:flex-row mb-10 px-5 py-6 rounded-b-lg space-x-4">
    <div class="flex-none mx-2 md:mx-0">
        @if ($logo)
            @if ($logoIntials)
                <span class="text-6xl">
                    <x-avatar size="w-24 h-24" squared label="{{ $logoIntials }}" />
                </span>
            @endif

            @if ($logoSrc)
                <x-avatar size="w-24 h-24" squared  src="{{ $logoSrc }}" alt="{{ $item->name }}" />
            @endif
        @endif
    </div>

    <div class="w-full flex flex-col justify-between mx-2">
        <h1 class="text-5xl">{{ $item->name }}</h1>
        <div class="text-lg mt-2 leading-snug">{{ $item->description }}</div>
    </div>

    <div>
        {{ $slot }}
    </div>

</div>
