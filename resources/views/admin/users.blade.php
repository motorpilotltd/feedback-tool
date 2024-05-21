<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Users') }}
        </h2>
    </x-slot>
    <x-admin.container>
        <x-tabs.container active="#admins">
            <x-tabs.tab name="Admins" tabLink="admins">
                <livewire:admin.admin-users-table/>
            </x-tabs.tab>

            <x-tabs.tab name="Suspend" tabLink="suspend">
                <livewire:admin.banned-users-table/>
            </x-tabs.tab>

            <x-tabs.tab name="All Users" tabLink="allusers">
                <livewire:admin.all-users-table/>
            </x-tabs.tab>
        </x-tabs.container>
    </x-admin.container>
</x-app-layout>
