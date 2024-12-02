@inject('aadSettings', 'App\Settings\AzureADSettings')
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />

            @if (session('error'))
                <x-card color="border border-red-500 bg-red-100" shadow="shadow-sm" class="text-red-500">
                    {!! session('error') !!}
                </x-card>
            @endif

            @if (session('status'))
            <x-card color="border border-green-500 bg-green-100" shadow="shadow-sm" class="text-green-500">
                {!! session('status') !!}
            </x-card>
            @endif

        </x-slot>

        <x-validation-errors class="mb-4" />

        <form id="login-form" method="POST" action="{{ route('login') }}" class="py-4">
            @csrf
            <div>
                <x-input
                    label="{{ __('text.form:email') }}"
                    id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    placeholder="{{ __('text.placeholder:email') }}"
                    :value="old('email')"
                    required
                    autofocus
                />
            </div>

            <div class="mt-4">
                <x-input
                    label="{{ __('text.form:password') }}"
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    placeholder="{{ __('text.placeholder:password') }}"
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
