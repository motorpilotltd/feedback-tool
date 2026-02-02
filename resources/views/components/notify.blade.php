<!-- Alert overlay notification -->
@session('notify')
<x-overlay-alert
    :redirect="true"
    messageToDisplay="{{ ($value['message']) }}"
    type="{{ ($value['type']) }}"
/>
@endsession
<x-overlay-alert />
