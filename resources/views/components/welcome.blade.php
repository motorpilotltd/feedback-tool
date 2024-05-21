@inject('settings', 'App\Settings\GeneralSettings')

<div class="bg-yellow-base flex flex-col md:flex-row mb-10 px-5 py-6 rounded-b-lg">
    <div>

        <h1 class="text-5xl">{{ $settings->welcome_title ?? __('general.welcome_to_feedback') }}</h1>
        <div class="text-lg mt-2 leading-snug">{{ $settings->welcome_description ?? __('general.here_you_can_suggest') }}</div>
    </div>

</div>
