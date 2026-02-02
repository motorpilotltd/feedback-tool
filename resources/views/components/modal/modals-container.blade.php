@props([
    'category' => null,
    'product'  => null,
    'idea'     => null
])

<div>
@auth
    @isset($idea)
        <livewire:modal.edit-comment :idea="$idea->id" />
    @endisset
    <livewire:modal.mark-comment-spam />
    <livewire:modal.comment-not-spam />
@endauth

<livewire:modal.view-image />

</div>
