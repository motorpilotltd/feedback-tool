@inject('aadSettings', 'App\Settings\AzureADSettings')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 py-4 space-y-4 overflow-hidden flex flex-col sm:justify-center items-center">
        {{ $logo }}
    </div>
    @if (!$aadSettings->aad_only || request()->get('login') == 'show')
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    @endif
    @if(isset($other))
        <div class="w-full sm:max-w-md py-4 overflow-hidden sm:rounded-lg flex flex-col items-center">
            {{ $other }}
        </div>
    @endif
</div>
