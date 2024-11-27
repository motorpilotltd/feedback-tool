<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-input
                    id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    required
                    autofocus
                    label="{{ __('text.form:email') }}"
                    placeholder="{{ __('text.placeholder:email') }}"
                />
            </div>

            <div class="mt-4">
                <x-input
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    label="{{ __('text.form:newpassword') }}"
                    placeholder="{{ __('text.placeholder:password') }}"
                />
            </div>

            <div class="mt-4">
                <x-input
                    id="password_confirmation"
                    class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    label="{{ __('text.form:confirmnewpassword') }}"
                    placeholder="{{ __('text.placeholder:password') }}"
                />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button info class="ml-4" type="submit">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
