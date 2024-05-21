<div class="people">
    @if ($people->isNotEmpty())
        <div class="flex flex-col mt-2 border-t-2 border-gray-100">
            <h1 class="text-lg font-medium">People:</h1>
            @foreach ($people as $person)
                    <div class="flex flex-row mt-2 items-center space-x-2">
                        <x-user-avatar xs :user="$person" />
                        <div class="text-sm font-medium">
                            {!! __('general.user_name_link', [
                                'name' => Str::limit($person->name, 25, '...'),
                                'link' => route('user.viewprofile', ['user' => $person->id])
                            ]) !!}
                            @if (!auth()->guest() && $person->id === auth()->user()->id)
                                <small>{!! __('text.you') !!}</small>
                            @endif
                        </div>
                    </div>
            @endforeach
        </div>
    @endif
</div>
