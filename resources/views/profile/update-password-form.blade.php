<x-form-section submit="updatePassword">
    <x-slot name="title">

        @if(!auth()->user()->isSocialiteHasNoPassword())
            {{ __('Update Password') }}
        @else
            {{ __('Set Manual Login Password') }}
        @endif
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        @if(!auth()->user()->isSocialiteHasNoPassword())
            <div class="col-span-6 sm:col-span-4">
                <x-input label="{{ __('Current Password') }}" placeholder="{{ __('text.placeholder:password') }}" id="current_password" type="password" class="mt-1 block w-full" wire:model="state.current_password" autocomplete="current-password" />
                <x-input-error for="current_password" class="mt-2" />
            </div>
        @endif

        <div class="col-span-6 sm:col-span-4">
            <x-input label="{{ __('New Password') }}" placeholder="{{ __('text.placeholder:password') }}" id="password" type="password" class="mt-1 block w-full" wire:model="state.password" autocomplete="new-password" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-input label="{{ __('Confirm Password') }}" placeholder="{{ __('text.placeholder:password') }}" id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="state.password_confirmation" autocomplete="new-password" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            <x-badge outline positive lg label="{{ __('Successfully saved.') }}" />
        </x-action-message>

        <x-button type="submit" info>
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
