@php
    $activerole = auth()->user()->hasRole(config('const.ROLE_SUPER_ADMIN'))
        ? config('const.ROLE_SUPER_ADMIN')
        : config('const.ROLE_PRODUCT_ADMIN');
    $active = Str::slug( Str::plural(__('text.role:' . $activerole)));
@endphp
<div>
    <div class="flex flex-row-reverse my-4">
        <x-button
            info
            md
            label="{{ __('text.button:grantrolepermission') }}"
            icon="user"
            wire:click="addRolePermissionModal"
        />
    </div>
    <div class="space-y-8">
        @can(config('const.PERMISSION_SYSTEM_MANAGE'))
            {{-- Only super admins can see superadmin tab --}}
            <x-card title="Super Admin" class="flex flex-col space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                    <x-input
                        id="searchSuperUser"
                        wire:model.live.debounce.500ms="searchSuperUser"
                        right-icon="search"
                        placeholder="{{ __('text.searchuserbynameemail') }}"
                    />
                </div>
                <x-table wire:loading.class.delay="opacity-30" wire:target='searchSuperUser'>
                    <x-slot name="head">
                        <x-table.heading>{{ __('text.id') }}</x-table.heading>
                        <x-table.heading>{{ __('text.name') }}</x-table.heading>
                        <x-table.heading>{{ __('text.email') }}</x-table.heading>
                        <x-table.heading>{{ __('text.dateregistered') }}</x-table.heading>
                        <x-table.heading>{{ __('text.actions') }}</x-table.heading>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($superadmins as $admin)
                            <x-table.row>
                                <x-table.cell>
                                    {{ $admin->id }}
                                </x-table.cell>
                                <x-table.cell>
                                    <div class="flex flex-row space-x-2">
                                        <x-user-avatar sm :user="$admin" />
                                        <span class="pt-1" title="{{ $admin->name }}">
                                            {!! __('general.user_name_link', [
                                                'name' => Str::limit($admin->name, 25, '...'),
                                                'link' => route('user.viewprofile', ['user' => $admin->id])
                                            ]) !!}
                                            @if ($admin->id === auth()->user()->id)
                                                <small>{!! __('text.you') !!}</small>
                                            @endif
                                        </span>
                                    </div>
                                </x-table.cell>
                                <x-table.cell>
                                    {{ $admin->email }}
                                </x-table.cell>
                                <x-table.cell>
                                    {{ $admin->created_at->toDayDateTimeString() }}
                                </x-table.cell>
                                <x-table.cell>
                                    @if (auth()->user()->id !== $admin->id)
                                        <x-button
                                            amber
                                            sm
                                            icon="pencil"
                                            label="{{ __('Edit') }}"
                                            wire:click="addRolePermissionModal({{ $admin->id }})"
                                        />
                                        <x-button
                                            negative
                                            sm
                                            icon="x"
                                            label="{{ __('Revoke') }}"
                                            wire:click="revokeDialog({{ $admin->id }}, '{{ config('const.ROLE_SUPER_ADMIN') }}')"
                                        />
                                    @endif
                                </x-table.cell>
                            </x-table.row>
                        @empty
                            <x-table.no-data>
                                {{ __('text.nodata') }}
                            </x-table.no-data>
                        @endforelse

                    </x-slot>
                </x-table>
                <div>
                    {{ $superadmins->links() }}
                </div>
            </x-card>
        @endif
        <x-card title="Product Admin" class="flex flex-col space-y-4">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                <x-input
                    id="searchUser"
                    wire:model.live.debounce.500ms="searchUser"
                    right-icon="search"
                    placeholder="{{ __('text.searchuserbynameemail') }}"
                />
                @if ($products->count() > 1)
                    <x-select
                        placeholder="Product"
                        wire:model.live="selectProduct"
                        :options="$products"
                        option-label="name"
                        option-value="id"
                    />
                @endif
            </div>
                {{-- Filter by product --}}
            <x-table wire:loading.class.delay="opacity-30" wire:target='searchUser,selectProduct'>
                <x-slot name="head">
                    <x-table.heading>{{ __('text.id') }}</x-table.heading>
                    <x-table.heading>{{ __('text.name') }}</x-table.heading>
                    <x-table.heading>{{ __('text.email') }}</x-table.heading>
                    <x-table.heading>{{ __('text.dateregistered') }}</x-table.heading>
                    <x-table.heading>{{ __('text.permissions') }}</x-table.heading>
                    @can(config('const.PERMISSION_SYSTEM_MANAGE'))
                        <x-table.heading>{{ __('text.actions') }}</x-table.heading>
                    @endcan
                </x-slot>
                <x-slot name="body">
                    @forelse ($productadmins as $admin)
                        <x-table.row>
                            <x-table.cell>
                                {{ $admin->id }}
                            </x-table.cell>
                            <x-table.cell>
                                <div class="flex flex-row space-x-2">
                                    <x-user-avatar sm :user="$admin" />
                                    <span class="pt-1">
                                        {!! __('general.user_name_link', [
                                            'name' => $admin->name,
                                            'link' => route('user.viewprofile', ['user' => $admin->id])
                                        ]) !!}
                                        @if ($admin->id === auth()->user()->id)
                                            <small>{!! __('text.you') !!}</small>
                                        @endif
                                    </span>
                                </div>
                            </x-table.cell>
                            <x-table.cell>
                                {{ $admin->email }}
                            </x-table.cell>
                            <x-table.cell>
                                {{ $admin->created_at->toDayDateTimeString() }}
                            </x-table.cell>
                            <x-table.cell>
                                @if($admin->permissionProduct)
                                    <div class="flex flex-col justify-between space-y-4">
                                    @foreach ($admin->permissionProduct as $item)
                                        <div class="flex flex-row space-x-2 justify-between">
                                            <x-link
                                                :link="$item['product']->link"
                                                :label="$item['product']->name"
                                                labelLimit="50"
                                            />
                                            @if ($admin->id !== auth()->user()->id)
                                                @can($item['permission'])
                                                    <x-button
                                                        red
                                                        2xs
                                                        icon="x"
                                                        primary
                                                        label="{{ __('Revoke') }}"
                                                        wire:click="revokeDialog({{ $admin->id }}, null, {{ json_encode($item) }})"
                                                    />
                                                @endcan
                                            @endif
                                        </div>

                                    @endforeach
                                    </div>
                                @endif
                            </x-table.cell>
                            @can(config('const.PERMISSION_SYSTEM_MANAGE'))
                                <x-table.cell>
                                        @if(count($admin->permissionProduct) > 0 && auth()->user()->id !== $admin->id)
                                            <x-button
                                                amber
                                                sm
                                                icon="pencil"
                                                label="{{ __('Edit') }}"
                                                wire:click="addRolePermissionModal({{ $admin->id }})"
                                            />
                                        @endif
                                        @if(count($admin->permissionProduct) > 1 && auth()->user()->id !== $admin->id)
                                            <x-button
                                                negative
                                                sm
                                                icon="x"
                                                label="{{ __('Revoke All') }}"
                                                wire:click="revokeDialog({{ $admin->id }}, '{{ config('const.ROLE_PRODUCT_ADMIN') }}')"
                                            />
                                        @endif
                                </x-table.cell>
                            @endcan
                        </x-table.row>
                    @empty
                        <x-table.no-data>
                            {{ __('text.nodata') }}
                        </x-table.no-data>
                    @endforelse
                </x-slot>
            </x-table>
            <div>
                {{ $productadmins->links() }}
            </div>
        </x-card>
    </div>

    {{-- Modals --}}
    <x-modal.card-custom title="Grant Role/Permission" blur wire:model="showModal">
        <x-modal.content>
            <x-modal.row>
                <x-select
                    wire:key="select-user-id"
                    id="selectUserId"
                    label="User"
                    wire:model.prefetch="userId"
                    x-on:selected="$wire.loadSelectedUserId();"
                    placeholder="Search a user..."
                    :async-data="route('api.users.index')"
                    :disabled="$disabledUsersSelect"
                    option-label="name"
                    option-value="id"
                    option-description="email"
                    min-items-for-search="5"
                />
            </x-modal.row>
            <x-modal.row>
                <x-select
                    wire:key="select-role"
                    wire:model.live="roles"
                    multiselect
                    label="Administrator Role"
                    :disabled="$disabledRoleSelect"
                    placeholder="Select user's role"
                    :options="$rolesOption"
                    option-label="name"
                    option-value="id"
                />
            </x-modal.row>
            @if(in_array($productAdmin, $roles))
                <x-modal.row>
                    <x-select
                        wire:key="select-product-id"
                        wire:model="productIds"
                        multiselect
                        label="Product"
                        placeholder="Select a product to apply permission"
                        :options="$products"
                        option-label="name"
                        option-value="id"
                        :searchable="true"
                        min-items-for-search="3"
                    />
                </x-modal.row>
            @endif

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
