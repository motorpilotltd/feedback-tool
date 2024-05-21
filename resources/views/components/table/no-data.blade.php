<x-table.row>
    <x-table.cell colspan="100%">
        <div class="flex justify-center items-center text-gray-300 space-x-2">
            <x-icon.stop />
            <span class="font-medium py-8 text-gray-400 text-xl">
                {{ $slot }}
            </span>
        </div>
    </x-table.cell>
</x-table.row>
