<div class="all-users-table space-y-4">
    <div class="flex justify-between">
        <div class="flex w-1/2 space-x-4">
            <x-input
                id="searchUser"
                wire:model.live.debounce.500ms="searchUser"
                right-icon="search"
                placeholder="{{ __('text.searchuserbynameemail') }}"
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
</div>
