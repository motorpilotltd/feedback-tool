<div class="categories-table space-y-4">
    @empty($categories)
        <x-alertbox.warning>
            {{ __('text.noproductavail') }}
        </x-alertbox.warning>
    @else
        <div class="flex justify-between">
            @if($productId)
                <div class="flex w-1/2 space-x-4">
                    <x-input
                        id="search"
                        wire:model.live.debounce.500ms="search"
                        placeholder="{{ __('text.searchcategory') }}"
                        right-icon="search"
                    />

                </div>

                <div>
                    <x-button
                        info
                        md
                        label="{{ __('text.new') }}"
                        icon="plus"
                        wire:click="createModal"
                    />
                </div>
            @endif
        </div>
        <div class="flex-col space-y-4 shadow-outline">
            <x-table>
                <x-slot name="head">
                    <x-table.heading sortable wire:click="sortBy('id')" :direction="$sortField == 'id' ? $sortDirection : null">{{ __('text.id') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('name')" :direction="$sortField == 'name' ? $sortDirection : null">{{ __('text.name') }}</x-table.heading>
                    <x-table.heading>{{ __('text.description') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('ideas_count')" :direction="$sortField == 'ideas_count' ? $sortDirection : null">{{ __('text.ideas') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('users.name')" :direction="$sortField == 'users.name' ? $sortDirection : null">{{ __('text.addedby') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sortField == 'created_at' ? $sortDirection : null">{{ __('text.dateadded') }}</x-table.heading>
                    <x-table.heading>{{ __('text.actions') }}</x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @forelse ($categories as $category)
                        <x-table.row wire:loading.class.delay="opacity-30">
                            <x-table.cell>
                                {{ $category->id }}
                            </x-table.cell>

                            <x-table.cell>
                                <div class="flex">
                                    <a href="{{ $category->link }}" class="group inline-flex space-x-2 truncate text-sm leading-5 text-blue-base">
                                        <x-icon name="external-link" class="w-4 h-4 mt-1" />
                                        <p class="truncte group-hover:text-blue-hover transition ease-in-out duration-150">
                                            {{ Str::limit($category->name, 35, '...')  }}
                                        </p>
                                    </a>
                                </div>
                            </x-table.cell>

                            <x-table.cell>
                                {{ Str::limit($category->description, 35, '...')  }}
                            </x-table.cell>

                            <x-table.cell>
                                {{ $category->ideas_count }}
                            </x-table.cell>

                            <x-table.cell>
                                <x-user-avatar-name-email
                                    :user="$category->user"
                                />
                            </x-table.cell>

                            <x-table.cell>
                                <span class="text-sm">
                                    {{ $category->created_at->toDayDateTimeString() }}
                                </span>
                            </x-table.cell>

                            <x-table.cell class="text-center">
                                <x-button.circle
                                    icon="pencil"
                                    wire:click="editModal({{ $category->id }})"
                                    outline
                                    amber
                                />
                                <x-button.circle
                                    icon="trash"
                                    wire:click="deleteModal({{ $category->id }})"
                                    outline
                                    red
                                />
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.no-data>
                            <div class="flex flex-col">
                                @if (!$productId)
                                    {{ __('text.selectaproductfirst') }}
                                @else
                                    {{ __('text.categorynotfound') }}
                                @endif
                            </div>
                        </x-table.no-data>
                    @endforelse
                </x-slot>
            </x-table>
            <div>
                {{ $categories->links() }}
            </div>
        </div>
    @endempty

    <x-modal.card-custom title="{{ $modalTitle }}" blur wire:model="showEditModal">
            <x-modal.content>
                <x-modal.row>
                    <x-input.group
                        for="name"
                        label="{{ __('Name') }}"
                        :borderT="false"
                        :error="$errors->first('editing.name')"
                    >
                        <x-input wire:model="editing.name" id="name" type="text" class="w-full"/>
                    </x-input.group>
                </x-modal.row>

                <x-modal.row>
                    <x-input.group
                        for="description"
                        label="{{ __('Description') }}"
                        :error="$errors->first('editing.description')"
                    >
                        <x-input.textarea wire:model="editing.description" id="description" type="text" class="w-full"/>
                    </x-input.group>
                </x-modal.row>
            </x-modal.content>
            <x-slot name="footer">
                <x-modal.button-container>
                    <x-button
                        primary
                        label="{{ __('text.save') }}"
                        wire:click="save"
                    />
                    <x-button
                        flat
                        label="{{ __('text.cancel') }}"
                        wire:click="$set('showEditModal', false)"
                    />
                </x-modal.button-container>
            </x-slot>
    </x-modal.card-custom>

    <x-modal.card-custom title="{{ $modalTitle }}" wire:model.live="showDeleteModal">
        <x-modal.content>
            <x-modal.row>
                @if ($editing->exists)
                    <span>{{ __('text.deletecategory', ['category' => $editing->name]) }}</span>
                        @if (isset($editing->ideas_count) && $editing->ideas_count > 0)
                            <x-alert type="warning">
                                {{ __('text.deletecategorywarning', ['num' => $editing->ideas_count]) }}
                            </x-alert>
                        @endif
                @endif
            </x-modal.row>
        </x-modal.content>
        <x-slot name="footer">
            <x-modal.button-container>
                <x-button
                    primary
                    label="{{ __('text.save') }}"
                    wire:click="delete"
                />
                <x-button
                    flat
                    label="{{ __('text.cancel') }}"
                    wire:click.defer="$set('showDeleteModal', false)"
                />
            </x-modal.button-container>
        </x-slot>
    </x-modal.card-custom>

</div>
