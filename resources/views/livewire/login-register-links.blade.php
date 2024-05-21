<div class="flex flex-row">
    <a
        wire:click.prevent="redirectToLogin"
        href="{{ route('login') }}"
        class="text-sm text-gray-700 hover:text-blue-500"
    >
        Login
    </a>

    @if (Route::has('register'))
        <a
            wire:click.prevent="redirectToRegister"
            href="{{ route('register') }}"
            class="ml-4 text-sm text-gray-700 hover:text-blue-500"
        >
            Register
        </a>
    @endif
</div>
