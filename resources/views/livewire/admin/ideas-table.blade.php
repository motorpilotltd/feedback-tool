<div class="ideas-table space-y-4">
    @empty($productId)
        <x-alertbox.warning>
            {{ __('text.noideaavail') }}
        </x-alertbox.warning>
    @else
        <div class="flex flex-col">
            <div class="flex w-full space-x-4 justify-between">
                <x-input
                    id="search"
                    type="text"
                    class=""
                    wire:model.live.debounce.500ms="filters.search"
                    placeholder="{{ __('text.searchidea') }}"
                />
                <div class="flex space-x-2">
                    @if ($ideas->isNotEmpty())
                        <x-button
                            icon="document-text"
                            teal
                            wire:click="exportCsv"
                        >
                            @if ($count = count($selected))
                                {{ __('text.exportselected', ['num' => $count]) }}
                            @else
                                {{ __('text.export') }}
                            @endif
                        </x-button>
                    @endif
                    <x-button
                        icon="{{ $showFilters ? 'x' : 'filter' }}"
                        info
                        wire:click="$toggle('showFilters')"
                        label="{{ __('text.filter') }}"
                    />
                </div>
            </div>

            {{-- Idea filters --}}
            <div>
                @if ($showFilters)
                    <div class="bg-gray-200 p-4 rounded shadow-inner flex relative my-2">
                        <div class="w-1/2 pr-2 space-y-4">
                            <h1>{{ __('text.status') }}</h1>
                            @foreach ($statuses as $status)
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        wire:model.live="filters.statuses"
                                        value="{{ $status->slug }}"
                                        class="rounded border-2 border-{{ $status->color }}-500 text-{{ $status->color }}-500"
                                    />
                                    <span class="ml-2 text-gray-600">{{ $status->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="w-1/2 pr-2 space-y-4">
                            <h1>{{ __('text.category') }}</h1>
                            @forelse ($categories as $category)
                                <label class="flex items-center">
                                    <x-checkbox
                                        wire:model.live="filters.categories"
                                        value="{{ $category->slug }}"
                                    />
                                    <span class="ml-2 text-gray-600">{{ $category->name }}</span>
                                </label>
                            @empty
                                <span class="text-gray-500 italic">{{ __('text.noavailablecategories') }}</span>
                            @endforelse
                            <div class="flex">
                                <x-button
                                    outline
                                    info
                                    label="{{ __('text.resetfilters') }}"
                                    wire:click="resetFilters"
                                    class="absolute right-0 bottom-0 p-4 mb-4 mr-4"
                                />
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="flex-col space-y-4">
            <x-table class="table-fixed table-sticky">
                <x-slot name="head">
                    <x-table.heading class="sticky-col">
                        <x-checkbox wire:model.live="selectAll"/>
                    </x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('id')" :direction="$sortField == 'id' ? $sortDirection : null" class="sticky-col">{{ __('text.id') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('title')" :direction="$sortField == 'title' ? $sortDirection : null" class="sticky-col">{{ __('text.title') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('categories.name')" :direction="$sortField == 'categories.name' ? $sortDirection : null">{{ __('text.category') }}</x-table.heading>
                    <x-table.heading >{{ __('text.tags') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('statuses.name')" :direction="$sortField == 'idea_status' ? $sortDirection : null">{{ __('text.status') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('users.name')" :direction="$sortField == 'users.name' ? $sortDirection : null">{{ __('text.addedby') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('use_case_id')" :direction="$sortField == 'use_case_id' ? $sortDirection : null">{{ __('text.usecaseid') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('votes_count')" :direction="$sortField == 'votes_count' ? $sortDirection : null">{{ __('general.votes') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('business_value')" :direction="$sortField == 'business_value' ? $sortDirection : null">{{ __('text.businessvalue') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('time_criticality')" :direction="$sortField == 'time_criticality' ? $sortDirection : null">{{ __('text.timecriticality') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('complexity')" :direction="$sortField == 'complexity' ? $sortDirection : null">{{ __('text.complexity') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('wsjf')" :direction="$sortField == 'wsjf' ? $sortDirection : null">{{ __('text.wsjf') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('project_status')" :direction="$sortField == 'project_status' ? $sortDirection : null">{{ __('text.projectstatus') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sortField == 'created_at' ? $sortDirection : null">{{ __('text.dateadded') }}</x-table.heading>
                    <x-table.heading>{{ __('text.actions') }}</x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @forelse ($ideas as $idea)
                        <x-table.row
                            wire:loading.class='opacity-10'
                            wire:key='row-{{ $idea->id }}'
                            wire:target="filters.statuses, filters.categories, filters.search"
                        >
                            <x-table.cell class="sticky-col text-center">
                                <x-checkbox wire:model.live="selected" value="{{ $idea->id }}"/>
                            </x-table.cell>
                            <x-table.cell class="sticky-col text-center">
                                {{ $idea->id }}
                            </x-table.cell>
                            <x-table.cell class="sticky-col">
                                <div class="flex">
                                    <a href="{{ $idea->idea_link }}" target="_idea" class="group inline-flex space-x-2 truncate text-sm leading-5">
                                        <p class="truncte group-hover:text-blue-hover transition ease-in-out duration-150 text-blue-base">
                                            {{ Str::limit($idea->title, 35, '...')  }}
                                        </p>
                                    </a>
                                </div>
                            </x-table.cell>
                            <x-table.cell>
                                {{ $idea->category->name }}
                            </x-table.cell>
                            <x-table.cell>
                                {{ $idea->tags->pluck('name')->implode(', ') }}
                            </x-table.cell>
                            <x-table.cell>
                                <x-idea-status-badge :status="$idea->ideaStatus" />
                            </x-table.cell>
                            <x-table.cell>
                                <x-user-avatar-name-email
                                    :user="$idea->author"
                                />
                            </x-table.cell>
                            <x-table.cell>
                                {{ $idea->use_case_id }}
                            </x-table.cell>
                            <x-table.cell>
                                {{ $idea->votes_count }}
                            </x-table.cell>
                            <x-table.cell>
                                {{ $idea->business_value }}
                            </x-table.cell>
                            <x-table.cell>
                                {{ $idea->time_criticality }}
                            </x-table.cell>
                            <x-table.cell>
                                {{ $idea->complexity }}
                            </x-table.cell>
                            <x-table.cell>
                                {{ $idea->wsjf }}
                            </x-table.cell>
                            <x-table.cell>
                                {{ $idea->project_status }}
                            </x-table.cell>
                            <x-table.cell>
                                <span class="text-sm">
                                    {{ $idea->created_at->toDayDateTimeString() }}
                                </span>
                            </x-table.cell>

                            <x-table.cell class="text-center">
                                {{-- <x-button.circle
                                    wire:click="edit({{ $idea->id }})"
                                    icon="cog"
                                /> --}}
                                <x-button.circle
                                    wire:click="calculate({{ $idea->id }})"
                                    icon="calculator"
                                />
                                <x-button.circle
                                    wire:click="move({{ $idea->id }})"
                                    icon="switch-horizontal"
                                />
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.no-data>
                            {{ __('text.ideanotfound') }}
                        </x-table.no-data>
                    @endforelse

                </x-slot>
            </x-table>
            <div>
                {{ $ideas->links() }}
            </div>
        </div>
    @endempty
    <x-modal.card-custom title="{{ $modalTitle }}" blur wire:model="showCalculateModal">
            <x-modal.content>
                <x-modal.row>
                    <x-select
                        id="businessValue"
                        label="{{ __('text.businessvalue') }}"
                        :options="$calcNumbers"
                        wire:model.live="calcData.business_value"
                    />
                </x-modal.row>
                <x-modal.row>
                    <x-select
                        id="timeCriticality"
                        label="{{ __('text.timecriticality') }}"
                        :options="$calcNumbers"
                        wire:model.live="calcData.time_criticality"
                    />
                </x-modal.row>
                <x-modal.row>
                    <x-select
                        id="complexity"
                        label="{{ __('text.complexity') }}"
                        :options="$calcNumbers"
                        wire:model.live="calcData.complexity"
                    />
                </x-modal.row>
                <x-modal.row>
                    <x-input.group class="" for="wsjf" label="{{ __('text.wsjf') }}" helpText="{!! __('text.wsjfhelp') !!}">
                        <span class="@unless ($wsjf) text-gray-400 text-sm @endunless">{{ ($wsjf) ?: __('text.computing') }}</span>
                    </x-input.group>
                </x-modal.row>
            </x-modal.content>
            <x-slot name="footer">
                <x-modal.button-container>
                    <x-button
                        primary
                        label="{{ __('text.save') }}"
                        type="submit"
                        wire:click="saveCalculate"
                    />
                    <x-button
                        flat
                        label="{{ __('text.cancel') }}"
                        wire:click="$set('showCalculateModal', false)"
                    />
                </x-modal.button-container>
            </x-slot>
    </x-modal.card-custom>

    <x-modal.card-custom title="{{ $modalTitle }}" blur wire:model="showMoveModal">
        <x-modal.content>
            @if ($editing->exists)
                <x-modal.row>
                    <x-input.group class="" for="title" label="{{ __('text.title') }}" :borderT="false ">
                        <span class="text-gray-800">{{ $editing->title }}</span>
                    </x-input.group>
                </x-modal.row>
                <x-modal.row>
                    <x-input.group for="author" label="{{ __('text.author') }}">
                        <div class="flex flex-row space-x-2">
                            <x-avatar :user="$editing->author" />
                            <div class="text-gray-800 flex flex-col">
                                <span>{{ $editing->author->name ?? __('text.userdeleted') }}</span>
                                <span class="text-gray-500">{{ $editing->author->email ?? ''}}</span>
                            </div>
                        </div>
                    </x-input.group>
                </x-modal.row>
                <x-modal.row>
                    <x-input.group class="" for="category" label="{{ __('text.category') }}">
                        <span class="text-gray-800">{{ $editing->category->name }}</span>
                    </x-input.group>
                </x-modal.row>
                <x-modal.row>
                    <x-input.group class="" for="idealink" label="{{ __('text.url') }}">
                        <a href="{{ $editing->idea_link }}" target="_blank" class="text-blue-base">{{ $editing->idea_link }}</a>
                    </x-input.group>
                </x-modal.row>
                <x-modal.row>
                    <x-input.group class="" for="ideastatus" label="{{ __('text.status') }}">
                        <div class="w-1/2">
                            <x-idea-status-badge :status="$editing->ideaStatus" />
                        </div>
                    </x-input.group>
                </x-modal.row>
                <x-modal.row>
                    <x-input.group for="productSelect" label="{{ __('text.product') }}">
                        <x-input.select class="" wire:model.live="selectedProduct" id="productSelect">
                            @foreach ($selectProducts as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </x-input.select>
                    </x-input.group>
                </x-modal.row>
                <x-modal.row>
                    <x-input.group for="categorySelect" label="{{ __('text.category') }}">
                        <x-input.select class="" wire:model.live="selectedCategory" id="categorySelect">
                            <option value="0">{{ __('general.selectcategory') }}</option>
                            @foreach ($selectCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-input.select>
                        @error('selectedCategory')
                            <x-input.error>{{ $message }}</x-input.error>
                        @enderror
                    </x-input.group>
                </x-modal.row>
            @endif

        </x-modal.content>
        <x-slot name="footer">
            <x-modal.button-container>
                <x-button
                    primary
                    label="{{ __('text.save') }}"
                    type="submit"
                    wire:click.prevent="saveMove"
                />
                <x-button
                    flat
                    label="{{ __('text.cancel') }}"
                    wire:click="$set('showMoveModal', false)"
                />
            </x-modal.button-container>
        </x-slot>
    </x-modal.card-custom>

    <form wire:submit="save">
        <x-dialog-modal wire:model.live="showEditModal">
                <x-slot name="title">{{ $modalTitle }}</x-slot>
                <x-slot name="content">
                    EDIT FORM HERE
                </x-slot>
                <x-slot name="footer">
                    <x-modal.button-container>
                        <x-button
                            primary
                            label="{{ __('text.save') }}"
                            type="submit"
                        />
                        <x-button
                            flat
                            label="{{ __('text.cancel') }}"
                            wire:click="$set('showEditModal', false)"
                        />
                    </x-modal.button-container>
                </x-slot>
        </x-dialog-modal>
    </form>
</div>
