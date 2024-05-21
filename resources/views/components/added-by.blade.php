@props([
    'name' => __('text.userdeleted'),
    'date' => __('--/--/----'),
    'asLink' => null
])
<div class="text-gray-400">
    @if (!empty($asLink))
        {!!
            __('general.added_by_link', [
                'nameLink' => __('general.user_name_link', ['link' => $asLink, 'name' => $name]),
                'date' => $date,
            ])
        !!}
    @else
        {!! __('general.added_by', ['name' => $name, 'date' => $date]) !!}
    @endif

</div>
