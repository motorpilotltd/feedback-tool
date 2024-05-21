<div>
    <x-modal.modal-confirm
    livewire-event-to-open-modal="openCommentNotSpamModal"
    event-to-close-modal='commentMarkedAsNotSpam'
    modal-title="{{ __('text.commentnotspam') }}"
    modal-description="{!! __('text.commentremovespamconfirm') !!}"
    modal-confirm-button-text="{{ __('text.confirm') }}"
    wire-click='commentNotSpam'
/>

</div>
