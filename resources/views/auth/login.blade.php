@inject('aadSettings', 'App\Settings\AzureADSettings')
@push('styles')
<style>
    /* Hide WireUI validation styling until field is touched */
    #login-form > div:not(.validation-enabled) [with-validation-colors][form-wrapper],
    #login-form > div:not(.validation-enabled) [with-validation-colors][form-wrapper] *,
    #login-form > div:not(.validation-enabled) [with-validation-colors][form-wrapper] label {
        --tw-ring-opacity: 0 !important;
        --tw-ring-color: transparent !important;
        background-color: transparent !important;
    }
    #login-form > div:not(.validation-enabled) [with-validation-colors][form-wrapper] input,
    #login-form > div:not(.validation-enabled) [with-validation-colors][form-wrapper] input:invalid {
        border-color: rgb(209 213 219) !important; /* gray-300 */
        --tw-ring-opacity: 0 !important;
        --tw-ring-color: transparent !important;
        box-shadow: none !important;
        background-color: white !important;
    }
</style>
@endpush
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />

            @session('error')
                <x-card color="border border-red-500 bg-red-100" shadow="shadow-sm" class="text-red-500">
                    {!! $value !!}
                </x-card>
            @endsession

            @session('status')
            <x-card color="border border-green-500 bg-green-100" shadow="shadow-sm" class="text-green-500">
                {!! $value !!}
            </x-card>
            @endsession

        </x-slot>

        <x-validation-errors class="mb-4" />

        <form id="login-form" method="POST" action="{{ route('login') }}" class="py-4" x-data="{ emailTouched: false, passwordTouched: false }">
            @csrf
            <div :class="{ 'validation-enabled': emailTouched }">
                <x-input
                    label="{{ __('text.form:email') }}"
                    id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    placeholder="{{ __('text.placeholder:email') }}"
                    :value="old('email')"
                    x-on:blur="emailTouched = true"
                    required
                    autofocus
                />
            </div>

            <div :class="{ 'validation-enabled': passwordTouched }" class="mt-4">
                <x-input
                    label="{{ __('text.form:password') }}"
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    placeholder="{{ __('text.placeholder:password') }}"
                    x-on:blur="passwordTouched = true"
                    required
                    autocomplete="current-password"
                />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox label="{{ __('text.form:rememberme') }}" id="remember_me" name="remember" />
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('text.form:forgotpassword') }}
                    </a>
                @endif

                <x-button info class="ml-4" type="submit">
                    {{ __('text.form:login') }}
                </x-button>
            </div>
        </form>

        <x-slot name="other">
            @if ($aadSettings->aad_enable)
                @if (!$aadSettings->aad_only || request()->get('login') == 'show')
                    <div class="my-4">OR</div>
                @endif
                <div class="w-full px-1">
                    <x-button
                        info
                        x-data=""
                        type="button"
                        class="w-full flex focus:ring-0"
                        href="{{ route('auth.microsoft') }}"
                    >
                        <x-icon.microsoft class="mr-2 h-6 w-6"/>
                        {{ __('text.form:loginwithorg') }}
                    </x-button>
                </div>
            @endif
        </x-slot>
    </x-authentication-card>
</x-guest-layout>
