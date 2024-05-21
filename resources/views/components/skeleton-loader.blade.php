<div id="skeleton-loader-time()">
    @foreach (range(1,3) as $item)
        <div class="flex items-center transition duration-150 ease-in py-4 animate-pulse">
            <div class="bg-gray-200 rounded-xl w-20 h-20 ml-4"></div>
            <div class="flex-1 ml-6 space-y-2">
                <div class="bg-gray-200 w-full rounded h-4"></div>
                <div class="bg-gray-200 w-full rounded h-16"></div>
            </div>
        </div>
    @endforeach

</div>
