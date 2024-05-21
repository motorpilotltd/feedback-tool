<div class="filters space-y-4">
    <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
        @foreach ($filters as $filter)
            <button
                wire:click.prevent="getIdeasByFilter('{{ $filter }}')"
                class="@if($selectedFilter == $filter) ring-2 ring-blue-base @endif  border-none rounded-xl block appearance-none bg-white px-4 py-2 leading-snug focus:outline-none hover:shadow-card"
            >
                {{ __('text.filter:' . $filter) }}
            </button>
        @endforeach
    </div>
    <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-6 justify-between">
        @if (!empty($categories))
            <div class="category-filter-container w-full md:w-1/3 inline-block relative @if(!empty($selectedCategory)) ring-2 ring-blue-base @endif rounded-lg">
                <x-select
                    id="category"
                    name="category"
                    placeholder="Select category"
                    :options="$categories"
                    option-label="name"
                    option-value="slug"
                    wire:model.live="selectedCategory"
                    class="rounded-lg"
                />
            </div> <!-- end category-filter-container -->
        @endif

        <div
            x-data="{ isOpen: false }"
            class="status-filter-container w-full md:w-1/3 inline-block relative"
        >

            <button
                class="md:absolute @if(!empty($selectedStatuses)) ring-2 ring-blue-base @endif border border-gray-300 shadow-sm flex flex-row items-center  focus:border-2 focus:border-primary-500 justify-between appearance-none bg-white px-4 py-1.5 rounded-md leading-3 space-x-3 hover:shadow-card w-full"
                @click="isOpen = !isOpen"
            >
                <span class="text-base">Status</span>
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <ul
                class="absolute w-52 text-left font-normal bg-white shadow-dialog rounded-xl z-10 py-3 md:ml-8 top-10 space-y-2 right-2"
                x-cloak
                x-show.transition.origin.top.left="isOpen"
                @mouseleave="isOpen = false"
                @click.away="isOpen = false"
                @keydown.escape.window="isOpen = false"
            >
                @foreach($statuses as $status)
                    <li>
                            <label
                                class="flex flex-row items-center ml-4 space-x-2">
                                <input
                                    type="checkbox"
                                    value="{{ $status->slug }}"
                                    wire:model.live="selectedStatuses"
                                    class="rounded-xl border-2 border-{{ $status->color }}-500 text-{{ $status->color }}-500"
                                />
                                <span class="text-sm ml-2">{{ $status->name }}</span>
                            </label>
                    </li>
                @endforeach
                <li class="border-t pt-2">
                    <div class="flex items-center justify-between px-2 space-x-1">
                        <button
                            wire:click.prevent="allStatus"
                            type="button"
                            class="flex items-center w-1/2 justify-center text-xs bg-blue-base text-white font-semibold rounded-xl border border-blue-base hover:bg-blue-hover transition duration-150 ease-in px-6 py-3 disabled:opacity-50"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                            <span>All</span>
                        </button>
                        <button
                            wire:click.prevent="clearStatus"
                            type="button"
                            class="flex items-center w-1/2 justify-center text-xs bg-gray-200 font-semibold rounded-xl border border-gray-200 hover:border-gray-400 transition duration-150 ease-in px-6 py-3"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Clear</span>
                        </button>
                    </div>
                </li>
            </ul>
        </div> <!-- end status-filter-container -->
    </div>

</div> <!-- end filters -->
