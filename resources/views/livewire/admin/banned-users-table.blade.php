<div class="banned-users-table space-y-4">
    <div class="flex justify-between">
        <div class="flex w-1/2 space-x-4">
            <x-input
                id="searchUser"
                wire:model.live.debounce.500ms="searchUser"
                right-icon="magnifying-glass"
                placeholder="{{ __('text.searchuserbynameemail') }}"
            />

        </div>
        <div>
            <x-button
                warning
                md
                label="{{ __('text.suspenduser') }}"
                icon="x-mark"
                wire:click="suspendUserModal"
            />
        </div>
    </div>
    <div class="flex-col space-y-4 shadow-outline">
        <x-table wire:loading.class.delay="opacity-30" wire:target='searchSuperUser'>
            <x-slot name="head">
                <x-table.heading>{{ __('text.id') }}</x-table.heading>
                <x-table.heading>{{ __('text.name') }}</x-table.heading>
                <x-table.heading>{{ __('text.email') }}</x-table.heading>
                <x-table.heading>{{ __('text.dateregistered') }}</x-table.heading>
                <x-table.heading>{{ __('text.actions') }}</x-table.heading>
            </x-slot>
            <x-slot name="body">
                @forelse ($users as $user)
                    <x-table.row>
                        <x-table.cell>
                            {{ $user->id }}
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex flex-row space-x-2">
                                <x-user-avatar sm :user="$user" />
                                <span class="pt-1">
                                    {!! __('general.user_name_link', [
                                        'name' => $user->name,
                                        'link' => route('user.viewprofile', ['user' => $user->id])
                                    ]) !!}
                                    @if ($user->id === auth()->user()->id)
                                        <small>{!! __('text.you') !!}</small>
                                    @endif
                                </span>
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            {{ $user->email }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $user->created_at->toDayDateTimeString() }}
                        </x-table.cell>
                        <x-table.cell>
                            <x-button
                                green
                                sm
                                icon="check"
                                label="{{ __('text.unbanuser') }}"
                                wire:click="unsuspendUserDialog({{ $user->id }})"
                            />
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
            {{ $users->links() }}
        </div>
    </div>

    <x-modal.card-custom title="Suspend a user's account" blur wire:model="showModal">
        <x-modal.content>
            <x-modal.row>
                <x-select
                    label="User"
                    wire:model="userId"
                    placeholder="Search a user..."
                    :async-data="route('api.users.index')"
                    option-label="name"
                    option-value="id"
                    option-description="email"
                    min-items-for-search="5"
                />
            </x-modal.row>
        </x-modal.content>

        <x-slot name="footer">
            <x-modal.button-container>
                    <x-button
                        warning
                        label="{{ __('text.suspend') }}"
                        type="button"
                        wire:click="suspendUser"
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
