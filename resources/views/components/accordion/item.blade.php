@props(['name'])
<li
    class="relative border-b border-gray-200"
    x-data="{
        name: '{{ $name }}',
        show: false,
        item: '{{ Str::slug($name, '-'); }}'
    }"
>
    <button
        type="button"
        class="border-b border-gray-200 w-full relative flex items-center py-4 px-5 text-base text-white text-left bg-gray-400 rounded-none transition focus:outline-none"
        @click="activeItem = item";
    >
        <div class="flex items-center justify-between w-full">
            <span x-text="name" class="capitalize"></span>
            <x-icon.chevron-up :class="'text-white'" x-show="activeItem == item"/>
            <x-icon.chevron-down :class="'text-white'" x-show="activeItem !== item"/>
        </div>
    </button>

    <div
        class="relative overflow-hidden transition-all max-h-0 duration-700"
        style="" x-ref="{{ Str::slug($name, '-'); }}"
        x-bind:style="activeItem == item ? 'max-height: ' + $refs[item].scrollHeight + 'px' : ''"
    >
        <div class="p-6 flex flex-col space-y-4">
            {{ $slot }}
        </div>
    </div>

</li>
