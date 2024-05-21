@props([
    'status' => null
])
<x-badge
    :color="$status->color ?? 'gray'"
    :label="$status->name ?? ''"
/>
