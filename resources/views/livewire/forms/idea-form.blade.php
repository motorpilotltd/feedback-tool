<div class="mb-8">
    <x-card id="idea-form" title="{{ $formTitle }}">
        <x-modal.content>
            <x-modal.row>
                <x-input
                    wire:model="title"
                    x-ref="title"
                    label="Title"
                    placeholder="{{ __('text.whatsyouridea') }}"
                />
            </x-modal.row>
            <x-modal.row>
                @if (!empty($categories))
                    <x-modal.row>
                        <x-select
                            wire:model="category"
                            label="Category"
                            placeholder="{{ __('Select a category...') }}"
                            :options="$categories"
                            option-label="name"
                            option-value="id"
                            :searchable="true"
                            min-items-for-search="3"
                        />
                    </x-modal.row>
                @endif
            </x-modal.row>

            <x-modal.row>
                <x-textarea
                    wire:model="content"
                    label="Content"
                    placeholder="{{ __('text.tellaboutidea') }}"
                    rows="10"
                />
            </x-modal.row>
            <x-modal.row>
                @php
                    $section = 'idea'
                @endphp
                @if (!empty($idea))
                    <livewire:attachment.attach-file-preview :model="$idea"  :hasdelete="true"/>
                @endif
            </x-modal.row>
            <x-modal.row>
                <x-input.filepond
                    wire:model.live="attachments"
                    multiple
                    allowFileTypeValidation
                    acceptedFileTypes="{{ $allowedTypes }}"
                    allowFileSizeValidation
                    maxFileSize="{{ $allowedSize }}"
                />

                @error('attachments')
                    <x-input.error>{{ $message }}</x-input.error>
                @enderror
            </x-modal.row>
            <x-modal.row>
                @foreach ($tagGroups as $tg)
                    @if ($tg->admin_or_user === config('const.TAGS_USER')
                        || ($tg->admin_or_user === config('const.TAGS_ADMIN') && auth()->user()->can('update', $product))
                    )
                        <x-select
                            wire:model.live="selectedTags.tg_{{ $tg->id }}"
                            :label="$tg->name"
                            placeholder="Select tag(s)..."
                            multiselect
                            :options="$tg->tags"
                            option-label="name"
                            option-value="id"
                            :searchable="true"
                            min-items-for-search="0"
                            empty-message="{{ __('Tag not found...') }}"
                        >
                            <x-slot name="beforeOptions" class="p-2 flex justify-center" x-show="search.length !== 0">
                                @if (!$tg->is_managed)
                                    <x-button
                                        x-on:click="$wire.addUserTag(search, {{ $tg->id }}); search = ''"
                                        primary
                                        full
                                    >
                                        <span x-html="`Add <q><b>${search}</b></q> as new tag...`"></span>
                                    </x-button>
                                @endif
                            </x-slot>
                    </x-select>
                    @endif
                @endforeach
            </x-modal.row>
            @can('specifyAuthor', $product)
                <x-modal.row>
                    <x-card title="{{ __('text.specifyauthor') }}" color="bg-white border border-gray-200" shadow="shadow-sm">
                        <div class="flex flex-row space-x-4">
                            <x-radio id="exist-author" label="{{ __('text.user:existing') }}" wire:model.live="authorOption" value='1'/>
                            <x-radio id="new-author" label="{{ __('text.user:new') }}" wire:model.live="authorOption" value='0'/>
                        </div>
                        <x-modal.row class="border-t border-gray-200 pt-2 mt-2">
                            @if ($authorOption)
                                <x-select
                                    label="User"
                                    wire:model.live="authorId"
                                    placeholder="Search a user..."
                                    :async-data="route('api.users.index')"
                                    option-label="name"
                                    option-value="id"
                                    :template="[
                                        'name'   => 'user-option',
                                        'config' => ['src' => 'profile_image']
                                    ]"
                                    option-description="email"
                                />
                            @else
                                <x-input
                                    wire:model="newUser.name"
                                    label="Name"
                                    placeholder="Firstname Lastname"
                                />
                                    <x-input
                                    wire:model="newUser.email"
                                    label="Email"
                                    placeholder="user@example.com"
                                />
                            @endif
                        </x-modal.row>
                    </x-card>
                </x-modal.row>
            @endcan
        </x-modal.content>
        <x-slot name="footer">
            <x-modal.button-container>
                <x-button primary label="Save" wire:click="saveIdea" />
                <x-button flat label="Cancel" x-on:click="close" />
            </x-modal.button-container>
        </x-slot>
    </x-card>
</div>
