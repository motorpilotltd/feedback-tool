<div id="view-profile-container" class="flex flex-col space-y-4">
    <x-static-section>
        <x-slot name="title">
            {{ auth()->id() == $user->id ? __('text.myprofile') : __('text.profile') }}
        </x-slot>

        <x-slot name="description">
            {{ __('text.userprofileinfo') }}
        </x-slot>

        <x-slot name="form">
            <!-- Profile Photo -->
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                    <x-label for="photo" value="{{ __('Photo') }}" />

                    <!-- Current Profile Photo -->
                    <div class="mt-2" x-show="! photoPreview">
                        <x-user-avatar
                            size="w-20 h-20 text-4xl"
                            :user="$user"
                        />
                    </div>
                </div>
            @endif

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <div>Name:</div>
                <div class="text-gray-400">{{ $user->name }}</div>
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <div>Email:</div>
                @if (auth()->user()->hasRole(config('const.ROLE_SUPER_ADMIN')) || auth()->id() == $user->id)
                    <div class="text-gray-400">{{ $user->email }}</div>
                @else
                    <div class="text-gray-400">{{ hideEmailAddress($user->email) }}</div>
                @endif
            </div>

            <div class="col-span-6 sm:col-span-4">
                <div>Status:</div>
                <div class="text-gray-400">
                    @if (!empty($user->banned_at))
                        {{-- Carbon time not working on banned_at field? --}}
                        <b class="text-orange-500">Banned</b> since {{ $user->banned_at->toDayDateTimeString() }}
                    @else
                        <b class="text-green-500">Active</b> since {{ $user->created_at->toDayDateTimeString() }}
                    @endif
                </div>
            </div>
        </x-slot>
        @if (auth()->id() == $user->id )
            <x-slot name="actions">
                <a href="{{ route('profile.show') }}">
                    <x-button info wire:loading.attr="disabled">
                        {{ __('text.editmyprofile') }}
                    </x-button>
                </a>
            </x-slot>
        @endif
    </x-static-section>
    <x-static-section>
        <x-slot name="title">
            {{ __('text.myideas')}}
        </x-slot>

        <x-slot name="description">
            {{ __('text.mycurrentideas') }}
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 space-y-2">
                @forelse ($ideas as $idea)
                    <livewire:idea.card
                        :key="$idea->getForLivewireKey()"
                        :idea="$idea"
                        :votesCount="$idea->votes_count"
                        :isViewOnly="true"
                    />
                @empty
                    <x-no-items-available :items="__('ideas')" />
                @endforelse
            </div>
        </x-slot>
    </x-static-section>
</div>
