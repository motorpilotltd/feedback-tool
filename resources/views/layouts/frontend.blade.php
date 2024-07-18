@inject('settings', 'App\Settings\GeneralSettings')
@props(['product' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        @if ($settings->ga_measurement_id)
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer', '{{ $settings->ga_measurement_id }}');</script>
            <!-- End Google Tag Manager -->
        @endif

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest" crossorigin="use-credentials">
        <title>{{ $settings->title ?? config('app.name', 'Feedback App') }}{{ !empty($title) ? ' | ' . $title: ''}}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        @livewireStyles

        @stack('styles')

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans bg-gray-background text-gray-900 text-sm">
        @if ($settings->ga_measurement_id)
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $settings->ga_measurement_id }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        @endif
        <x-dialog z-index="z-50" blur="md" align="center" />
        <x-notifications position="top-right" z-index="z-50"/>
        <header class="flex flex-col md:flex-row items-center justify-between px-8 py-1 bg-white shadow-md mb-4 sticky top-0 z-10">
            <a href="/" class="flex items-center justify-between space-x-2">
                <img src="{{ asset('img/logo.png') }}" alt="logo" class="w-8">
                <span class="font-semibold text-lg">{{ $settings->title ?? config('app.name') }}</span>
            </a>
            <div class="flex items-center mt-2 md:mt-0">
                @if (Route::has('login'))
                    <div class="right-0 py-4 flex flex-row items-center space-x-2">
                        @if (!request()->routeIs('frontend.search.index'))
                            <livewire:modal.search />
                        @endif
                        @auth
                            <livewire:show-links-dropdown />
                            <div class="flex flex-row items-center divide-x divide-dashed hover:divide-solid">
                                <!-- Notification bell -->

                                <livewire:notification-bell />
                                <!-- Dropdown -->
                                <x-user-dropdown-nav />
                            </div>
                        @else
                            <livewire:login-register-links />
                        @endauth
                    </div>
                @endif
            </div>
        </header>

        <main class="container flex flex-col md:flex-row max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="w-full px-2 md:px-0 @if(!in_array(request()->route()->getName() , ['profile.show', 'user.viewprofile', 'user.myprofile']) ) @endif">
                <div class="mt-8">
                    {{ $slot }}
                </div>
            </div>
            {{-- Product Side Bar  --}}
            @if (!empty($product))
                <livewire:side-bar.container :product='$product' />
            @endif
        </main>

        <x-notify />

        @wireUiScripts
        @livewireScripts
        @stack('scripts')
    </body>
</html>
