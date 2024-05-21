<x-action-section>
    <x-slot name="title">
        {{ __('Login As') }}
    </x-slot>

    <x-slot name="description">

    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Login as this user') }}
        </div>

        <div class="mt-5">
            <x-button
                red
                wire:click="loginUser('{{$user->id}}')"
                label="{!! __('text.loginas:user', ['user' => $user->name]) !!}"
                wire:loading.attr="disabled"
            />
        </div>
    </x-slot>
</x-action-section>
