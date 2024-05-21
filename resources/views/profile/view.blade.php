<x-frontend-layout>
    <x-slot name="title">
        {{ __('View Profile') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Profile') }}
        </h2>
    </x-slot>
    {{ Breadcrumbs::render('viewprofile') }}
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:user.view-profile :user="$user->id"/>
        </div>

        @if (!session()->get('admin_user') && auth()->user()->hasRole(config('const.ROLE_SUPER_ADMIN')))
            @if (auth()->user()->id !== $user->id)
                <x-section-border />
                <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                    <livewire:user.login-as :user="$user->id"/>
                </div>
            @endif
        @endif
    </div>
</x-frontend-layout>
