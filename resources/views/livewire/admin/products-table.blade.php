<div class="products-table space-y-4">
    @empty($products)
        <x-alertbox.warning>
            {{ __('text.noproductavail') }}
        </x-alertbox.warning>
    @else
        <div class="flex justify-between">
            <div class="flex w-1/2 space-x-4">
                <x-input
                    id="search"
                    wire:model.live.debounce.500ms="search"
                    right-icon="magnifying-glass"
                    placeholder="{{ __('text.searchproduct') }}"
                />
            </div>
            <div>
                <x-button
                    blue
                    label="{{ __('text.new') }}"
                    wire:click="openCreateModal"
                    icon="plus"
                />
            </div>
        </div>
        <div class="flex-col space-y-4">
            <x-table>
                <x-slot name="head">
                    <x-table.heading sortable wire:click="sortBy('id')" :direction="$sortField == 'id' ? $sortDirection : null">{{ __('text.id') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('name')" :direction="$sortField == 'name' ? $sortDirection : null">{{ __('text.name') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('categories_count')" :direction="$sortField == 'categories_count' ? $sortDirection : null">{{ __('text.categories') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('ideas_count')" :direction="$sortField == 'ideas_count' ? $sortDirection : null">{{ __('text.ideas') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('users.name')" :direction="$sortField == 'users.name' ? $sortDirection : null">{{ __('text.addedby') }}</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sortField == 'created_at' ? $sortDirection : null">{{ __('text.dateadded') }}</x-table.heading>
                    <x-table.heading>{{ __('text.actions') }}</x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @forelse ($products as $product)
                        <x-table.row wire:loading.class.delay="opacity-30" wire:target="save">
                            <x-table.cell>
                                {{ $product->id }}
                            </x-table.cell>

                            <x-table.cell>
                                @if ($image = $product->getMedia('attachments')->first())
                                    <x-link
                                        :link="$product->link"
                                        :label="$product->name"
                                        logo="{{ route('file.attachments.show', ['display', $image->file_name]) }}"
                                        labelLimit="50"
                                    />
                                @else
                                    <x-link
                                        :link="$product->link"
                                        :label="$product->name"
                                        logoInitials="{{ Str::substr($product->name, 0, 2) }}"
                                        labelLimit="50"
                                    />
                                @endif

                            </x-table.cell>

                            <x-table.cell>
                                {{ $product->categories_count }}
                            </x-table.cell>

                            <x-table.cell>
                                {{ $product->ideas_count }}
                            </x-table.cell>

                            <x-table.cell>
                                <x-user-avatar-name-email
                                    :user="$product->user"
                                />
                            </x-table.cell>

                            <x-table.cell>
                                <span class="text-sm">
                                    {{ $product->created_at->toDayDateTimeString() }}
                                </span>
                            </x-table.cell>

                            <x-table.cell class="text-center">
                                <x-mini-button
                                    rounded
                                    outline
                                    blue
                                    wire:click="edit({{ $product->id }})"
                                    icon="cog"
                                />
                                <x-mini-button
                                    rounded
                                    outline
                                    red
                                    wire:click="deleteDialog({{ $product->id }})"
                                    icon="trash"
                                />

                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="7">
                                <div class="flex justify-center items-center text-gray-300 space-x-2">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                      </svg>
                                    <span class="font-medium py-8 text-gray-400 text-xl">
                                        {{ __('text.productnotfound') }}
                                    </span>
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-slot>
            </x-table>
            <div>
                {{ $products->links() }}
            </div>
        </div>
    @endempty

    <x-modal.card-custom title="{{ $modalTitle }}" blur wire:model="showModal">
        <x-modal.content>
            <x-modal.row>
                <x-input.group
                    for="newlogo"
                    :borderT="false "
                    label="{{ __('Logo') }}"
                >
                    <div
                        x-data="{ logoName: null, logoPreview: null }"
                        class="col-span-6 sm:col-span-4"
                        x-init="
                        $wire.on('logoPreviewReset', () => {
                            logoPreview = null;
                        })
                    "
                    >
                        <!-- Logo File Input -->
                        <input type="file" class="hidden"
                                    wire:model.live="newLogo"
                                    x-ref="newLogo"
                                    x-on:change="
                                        logoName = $refs.newLogo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            logoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.newLogo.files[0]);
                                    " />
                        <!-- Current Logo -->
                        <div class="mt-2" x-show="!logoPreview">

                            @empty(!$productLogo)
                                <x-avatar size="w-24 h-24" squared src="{{ route('file.attachments.show', ['display', $productLogo->file_name]) }}" />
                            @else
                                <span class="text-6xl">
                                    <x-avatar size="w-24 h-24" squared label="{{ Str::substr($editing->name, 0, 2) }}" />
                                </span>
                            @endif

                        </div>

                        <!-- New Logo Preview -->
                        <div class="mt-2" x-show="logoPreview">
                            <span class="block rounded w-24 h-24 bg-cover bg-no-repeat bg-center"
                                x-bind:style="'background-image: url(\'' + logoPreview + '\');'">
                            </span>
                        </div>

                        <x-secondary-button
                            class="mt-2 mr-2"
                            type="button"
                            x-on:click.prevent="$refs.newLogo.click()"
                            wire:loading.attr="disabled"
                            wire:target="newLogo,deleteLogo"
                        >
                            <span
                                wire:loading.class="hidden"
                                wire:target="newLogo"
                                >
                                {{ __('Select A New Logo') }}
                            </span>
                            <span class="hidden"
                                wire:loading.class.remove="hidden"
                                wire:loading.class="block"
                                wire:target="newLogo"
                            >
                                {{ __('Uploading...') }}
                            </span>

                        </x-secondary-button>

                        @if (!empty($productLogo) || !empty($newLogo))
                            <x-secondary-button
                                type="button"
                                class="mt-2"
                                wire:click="deleteLogo"
                                wire:loading.attr="disabled"
                                wire:target="newLogo,deleteLogo"
                            >
                            <span
                                wire:loading.class="hidden"
                                wire:target="deleteLogo"
                                >
                                {{ __('Remove logo') }}
                            </span>
                            <span class="hidden"
                                wire:loading.class.remove="hidden"
                                wire:loading.class="block"
                                wire:target="deleteLogo"
                            >
                                {{ __('Deleting logo...') }}
                            </span>
                            </x-secondary-button>
                        @endif

                        <x-input-error for="newLogo" class="mt-2" />
                    </div>
                </x-input.group>
            </x-modal.row>

            <x-modal.row>
                <x-input
                    wire:model="editing.name"
                    id="name"
                    type="text"
                    label="{{ __('Name') }}"
                    class="w-full"
                />
            </x-modal.row>

            <x-modal.row>
                <x-textarea
                    wire:model="editing.description"
                    id="description"
                    label="{{ __('Description') }}"
                    class="w-full"
                />
            </x-modal.row>

            <x-section-title>
                <x-slot name="title">{{ __('Additional Settings') }}</x-slot>
            </x-section-title>
            <x-modal.row>
                <x-input.group
                    for="hideFromProductList"
                    label="{{ __('text.hidefromproductlist') }}"
                    helpText="{!! __('text.hidefromproducthelp') !!}"
                    :error="$errors->first('settings.hideFromProductList')"
                >
                    <x-input.switch wire:model="settings.hideFromProductList" id="hideFromProductList"/>
                </x-input.group>
            </x-modal.row>
            <x-modal.row>
                <x-input.group
                    for="hideProductFromBreadcrumbs"
                    label="{{ __('text.hideproductsfrombreadcrumbs') }}"
                    helpText="{!! __('text.hideproductsfrombreadcrumbshelp') !!}"
                    :error="$errors->first('settings.hideProductFromBreadcrumbs')"
                >
                    <x-input.switch wire:model="settings.hideProductFromBreadcrumbs" id="hideProductFromBreadcrumbs"/>
                </x-input.group>
            </x-modal.row>

            <x-modal.row>
                <x-input
                    wire:model="settings.serviceDeskLink"
                    id="serviceDeskLink"
                    label="{{ __('text.serviceDeskLink') }}"
                    placeholder="https://www.example.com"
                />
            </x-modal.row>

            <x-modal.row>
                <x-input.group
                    for="enableAwaitingConsideration"
                    label="{{ __('text.enableawaitingconsideration') }}"
                    helpText="{!! __('text.enableawaitingconsiderationhelp') !!}"
                    :error="$errors->first('settings.enableAwaitingConsideration')"
                >
                    <x-input.switch wire:model="settings.enableAwaitingConsideration" id="enableAwaitingConsideration"/>
                </x-input.group>
            </x-modal.row>

            <x-modal.row>
                <x-input.group
                    for="enableSandboxMode"
                    label="{{ __('text.sandboxmode') }}"
                    helpText="{{ __('text.enablesandboxhelp') }}"
                    :error="$errors->first('settings.enableSandboxMode')"
                >
                    <x-input.switch wire:model="settings.enableSandboxMode" id="enableSandboxMode"/>
                </x-input.group>
            </x-modal.row>

            <x-modal.row>
                <x-input.group
                    for=""
                    label="{{ __('Links') }}"
                >
                    <livewire:forms.links-field :initialLinks="$links" />
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
                    wire:click="$set('showModal', false)"
                />
            </x-modal.button-container>
        </x-slot>
    </x-modal.card-custom>
</div>
