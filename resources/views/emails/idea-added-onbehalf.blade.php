@component('mail::message')
Hi {{ $idea->author->name }},

An idea was created and has set you as the _author_.

_Idea Title:_ {{ $idea->title }}

_Added by:_ {{ $idea->addedBy->name }}({{ $idea->addedBy->email }})

@component('mail::button', ['url' => route('idea.show', $idea)])
Go to Idea
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
