<div>
    <x-modal.modal-confirm
    livewire-event-to-open-modal="openMarkCommentSpamModal"
    event-to-close-modal='commentWasMarkedAsSpam'
    modal-title="{{ $title }}"
    modal-description="{!! $description !!}"
    modal-confirm-button-text="{{ __('text.confirm') }}"
    wire-click='markCommentSpam'
/>
</div>
