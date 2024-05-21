@props([
    'type' => 'info',
    'heading' => ''
])
@php
    $typeClass = '';
    switch($type) {
        case 'success':
            $typeClass = "bg-green-100 border-green-500 text-green-700";
            break;
        case 'danger':
            $typeClass = "bg-yellow-100 border-yellow-500 text-yellow-700";
            break;
        case 'warning':
            $typeClass = "bg-red-100 border-red-500 text-red-700";
            break;
        default:
            $typeClass = "bg-blue-100 border-blue-500 text-blue-700";
            break;
    }
@endphp
<div {{ $attributes->merge(['class' => 'border-l-4 px-4 py-2 ' . $typeClass]) }} role="alert">
    @unless ($heading)
        <p class="font-bold">{{ $heading }}</p>
    @endunless
    <p>{{ $slot }}</p>
</div>
