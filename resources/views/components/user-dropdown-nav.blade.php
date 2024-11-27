<div class="pl-2 relative user-dropdown-nav">
    <x-dropdown width="w-60" height="h-auto">
        <x-slot name="trigger">
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <button class="flex flex-row items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition h-">
                    <x-user-avatar
                        :user="auth()->user()"
                    />
                    <x-icon name="ellipsis-vertical" class="w-5 h-5 w-" />
                </button>
            @else
                <span class="inline-flex rounded-md">
                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition content-center">
                        {{ Auth::user()->name }}
                        <x-icon name="ellipsis-vertical" class="w-5 h-5 mt-2" />
                    </button>
                </span>
            @endif
        </x-slot>
        <x-dropdown.header>
            <x-slot name="label">
                <div class="flex flex-col">
                    <div class="font-bold whitespace-pre-line">{{ auth()->user()->name  }}</div>
                    <div class="text-xs text-gray-400 whitespace-pre-line">{{ auth()->user()->email  }}</div>
                    @if ($roles = auth()->user()->getRoleNames()->toArray())
                    <div class="text-xs text-gray-400 break-words">
                        <b>Role:</b> {{ Str::headline($roles[0]) }}
                    </div>
                @endif
                </div>
            </x-slot>
            <x-dropdown.item separator icon="user-circle" href="{{ route('user.myprofile') }}">
                {{ __('text.myprofile') }}
            </x-dropdown.item>
            <x-dropdown.item icon="user" href="{{ route('profile.show') }}">
                {{ __('text.profilesettings') }}
            </x-dropdown.item>

            @if ($adminUser = session()->get('admin_user'))
                <form method="POST" action="{{ route('user.loginas') }}">
                    @csrf
                    <x-dropdown.item
                        separator icon="users"
                        href="{{ route('user.loginas') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        {{ __('text.switchtouser', ['user' => 'Admin']) }}
                    </x-dropdown.item>
                </form>
            @endif


        </x-dropdown.header>
        @can(config('const.PERMISSION_PRODUCTS_MANAGE'))
            <x-dropdown.item icon="computer-desktop"  href="{{ route('admin.dashboard') }}">
                <div class="flex flex-col">
                {{ __('Admin Dashboard') }}
            </div>
            </x-dropdown.item>
        @endcan
        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown.item separator icon="arrow-right-on-rectangle"
                href="{{ route('logout') }}"
                onclick="event.preventDefault(); this.closest('form').submit();"
            >
                {{ __('Log Out') }}
            </x-dropdown.item>
        </form>
    </x-dropdown>
    {{-- <x-dropdown align="right">
        <x-slot name="trigger">
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->getAvatar() }}" alt="{{ Auth::user()->name }}" />
                </button>
            @else
                <span class="inline-flex rounded-md">
                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                        {{ Auth::user()->name }}

                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
            @endif
        </x-slot>

        <x-slot name="content">
            <div class="py-2">
                <div class="block px-4 text-md text-gray-400">
                    {{ auth()->user()->name }}
                </div>
                <div class="block px-4 text-xs text-gray-400">
                    {{ auth()->user()->email }}
                </div>
            </div>
            <div class="border-t border-gray-100"></div>


            <x-dropdown-link href="{{ route('profile.show') }}">
                {{ __('Profile') }}
            </x-dropdown-link>


            <div class="border-t border-gray-100"></div>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown> --}}
</div>
