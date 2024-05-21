@component('mail::message')
# A new ideas was added to product **{{ $idea->product->name }}**

By **{{ $idea->addedBy->name }}**:

"**{{ $idea->title }}**"

@component('mail::button', ['url' => route('idea.show', $idea)])
Go to Idea
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
