<div class="pb-8">
    @forelse ($statuses as $status)
        <x-card color="bg-white border-2 border-{{ $status->color }}-500 mb-4">
            <x-slot name="header">
                <h1 class="text-lg bg-{{ $status->color }}-500 py-1 px-2 text-white">
                    {{ $status->name }}
                </h1>
            </x-slot>
            <ul>
            @forelse ($status->ideas as $idea)
                <li class="text-base mb-2 group leading-snug">
                    <a
                        class="flex flex-row align-middle text-blue-base"
                        href="{{ route('idea.show', $idea) }}"
                        target="_blank"
                    >
                        <span><x-icon name="arrow-top-right-on-square" class="w-4 h-4 mr-1 mt-1" /></span>
                        <span class="">{{ $idea->title }}</span>
                    </a>
                    <span class="text-sm text-gray-400">
                        {{ $idea->author->name . ' - ' . $idea->created_at->toDayDateTimeString() }}
                    </span>
                    {{-- <x-added-by :name="$idea->author->name ?? null" :date="$idea->created_at->toDayDateTimeString()" /> --}}
                </li>
            @empty
                <x-no-items-available :items="__('ideas')" />
            @endforelse
            <ul>
        </x-card>
    @empty
        <x-no-items-available :items="__('status')" />
    @endforelse

</div>
