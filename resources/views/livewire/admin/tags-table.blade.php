<div class="tags-table space-y-4">
    @empty($tagGroups)
        <x-alertbox.warning>
            {{ __('text.noproductavail') }}
        </x-alertbox.warning>
    @else
        <div class="flex flex-row-reverse my-4 justify-between">
            @if($productId)
                {{-- <div class="flex w-1/2 space-x-4">
                    <x-input
                        id="search"
                        type="text"
                        class=""
                        wire:model.live="search"
                        placeholder="{{ __('text.searchtagsgroup') }}"
                    />
                </div> --}}
                <div>
                    <x-button
                        info
                        md
                        label="{{ __('text.new') }}"
                        icon="plus"
                        wire:click="tagGroupFormModal"
                    />
                </div>
            @endif
        </div>
        <div class="flex-col space-y-4 shadow-outline">
            <x-table>
                <x-slot name="head">
                    <x-table.heading>{{ __('text.id') }}</x-table.heading>
                    <x-table.heading>{{ __('text.name') }}</x-table.heading>
                    <x-table.heading>{{ __('text.managed') }}</x-table.heading>
                    <x-table.heading>{{ __('text.admin_or_user') }}</x-table.heading>
                    <x-table.heading>{{ __('text.number_of_tags') }}</x-table.heading>
                    <x-table.heading>{{ __('text.addedby') }}</x-table.heading>
                    <x-table.heading>{{ __('text.dateadded') }}</x-table.heading>
                    <x-table.heading>{{ __('text.datemodified') }}</x-table.heading>
                    <x-table.heading>{{ __('text.actions') }}</x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @forelse ($tagGroups as $row)
                        <x-table.row>
                            <x-table.cell>{{ $row->id }}</x-table.cell>
                            <x-table.cell>{{ $row->name }}</x-table.cell>
                            <x-table.cell>{{ __('text.tags:ismanage:' . $row->is_managed) }}</x-table.cell>
                            <x-table.cell>{{ __('text.tags:' . $row->admin_or_user) }}</x-table.cell>
                            <x-table.cell>{{ $row->tags_count }}</x-table.cell>
                            <x-table.cell>
                                <x-user-avatar-name-email
                                    :user="$row->user"
                                />
                            </x-table.cell>
                            <x-table.cell>{{ $row->created_at ? $row->created_at->diffForHumans() : '' }}</x-table.cell>
                            <x-table.cell>{{ $row->updated_at ? $row->updated_at->diffForHumans() : '' }}</x-table.cell>
                            <x-table.cell>
                                <x-mini-button
                                    rounded
                                    icon="pencil"
                                    wire:click="tagGroupFormModal({{ $row->id }})"
                                    outline
                                    orange
                                />
                                <x-mini-button
                                    rounded
                                    icon="trash"
                                    wire:click="deleteDialog({{ $row->id }})"
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
                                    {{ __('text.tagsgroupempty') }}
                                @endif
                            </div>
                        </x-table.no-data>
                    @endforelse
                </x-slot>
            </x-table>
        </div>
    @endif

    <x-modal.card-custom title="Tags Group" blur wire:model="showModal">
        <x-modal.content>
            <x-modal.row>
                <x-input
                    wire:model="tagGroupName"
                    label="Tag Group name"
                    placeholder="Type in Tag Group's name..."
                />
            </x-modal.row>
            <x-modal.row>
                <x-select
                    label="{{ __('text.managedorunmanaged') }}"
                    placeholder="Select..."
                    :options="[
                        ['name' => __('text.tags:ismanage:' . $managed),  'id' => $managed, 'description' => __('text.managed:tag:desc')],
                        ['name' => __('text.tags:ismanage:' . $unmanaged), 'id' => $unmanaged, 'description' => __('text.unmanaged:tag:desc')],
                    ]"
                    option-label="name"
                    option-value="id"
                    wire:model="isManaged"
                />
            </x-modal.row>
            <x-modal.row>
                <x-select
                    label="Admin or User"
                    placeholder="Select..."
                    :options="[
                        ['name' => __('text.admin'),  'id' => config('const.TAGS_ADMIN'), 'description' => __('text.admin:tag:description')],
                        ['name' => __('text.user'), 'id' => config('const.TAGS_USER'), 'description' => __('text.user:tag:description')],
                    ]"
                    option-label="name"
                    option-value="id"
                    wire:model="adminOrUser"
                />
            </x-modal.row>
            <x-modal.row>
                <x-select
                    label="Tags"
                    multiselect
                    wire:model="tagsSelected"
                    placeholder="Type in tags to add"
                    :options="$tagsOptions"
                    hide-empty-message
                    :searchable="true"
                    min-items-for-search="0"
                >
                    <x-slot name="beforeOptions" class="p-2 flex justify-center" x-show="search.length !== 0">
                        <x-button
                            x-on:click="$wire.addToTagsSelected(search); search = ''"
                            primary
                            full
                        >
                            <span x-html="`Add <q><b>${search}</b></q>...`"></span>
                        </x-button>
                    </x-slot>
                </x-select>
            </x-modal.row>
        </x-modal.content>

        <x-slot name="footer">
            <x-modal.button-container>
                    <x-button
                        primary
                        label="{{ __('text.save') }}"
                        type="button"
                        wire:click="save"
                    />
                    <x-button
                        flat
                        label="{{ __('text.cancel') }}"
                        x-on:click="close"
                    />
            </x-modal.button-container>
        </x-slot>
    </x-modal.card-custom>
</div>
