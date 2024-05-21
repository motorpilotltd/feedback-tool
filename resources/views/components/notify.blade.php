<!-- Alert overlay notification -->
@if (session('notify'))
<x-overlay-alert
    :redirect="true"
    messageToDisplay="{{ (session('notify')['message']) }}"
    type="{{ (session('notify')['type']) }}"
/>
@endif
<x-overlay-alert />
