<div
    class="flex bg-white rounded-lg comment-form-section"
    x-init="
    $wire.on('commentWasAdded', () => {
        isOpen = false
    })
    Livewire.interceptMessage(({ message, onFinish }) => {
        onFinish(() => {
            if (message.calls.some(call => ['gotoPage', 'previousPage', 'nextPage'].includes(call.method))) {
                const firstComment = document.querySelector('.comment-container:first-child')
                if (firstComment) firstComment.scrollIntoView({ behavior: 'smooth' })
            }
        })
    })
    @session('scrollToComment')
        const commenToScroll = document.querySelector('#comment-{{ $value }}')
        commenToScroll.scrollIntoView({ behavior: 'smooth' })
        commenToScroll.classList.add('border')
        commenToScroll.classList.add('border-green-base')
        setTimeout(() => {
            commenToScroll.classList.remove('border')
            commenToScroll.classList.remove('border-green-base')
        }, 5000)
    @endsession
    "

    {{-- Scroll to the latest comment added --}}
    @scroll-to-latest-comment.window="
        $nextTick(() => {
            comments = document.querySelectorAll('.comment-container:not(.pinned-comment)');
            latestComment = comments[0]
            latestComment.classList.add('border')
            latestComment.classList.add('border-green-base')
            setTimeout(() => {
                latestComment.classList.remove('border')
                latestComment.classList.remove('border-green-base')
            }, 5000)
        });
    "

    @scroll-to-pinned-comment.window="
        comments = document.querySelectorAll('.comment-container .pinned-scroll-comment')
        console.log('test', comments)
        latestComment = comments[0]
        latestComment.scrollIntoView({behavior: 'smooth'})
    "
>
    @auth
        <livewire:forms.comment-form wire:key='addcomment' :idea="$idea->id"  action="add"/>
    @else
        <div class="px-4 py-6 w-full">
            <p class="font-normal text-center text-gray-400">{{ __('general.loginregistertocomment') }}</p>
            <div class="flex flex-row items-center justify-center space-x-3 mt-2">
                <x-button
                    info
                    label="{{ __('general.login') }}"
                    x-ref="comment"
                    wire:click.prevent="redirectToLogin"
                />
                <x-button
                    default
                    label="{{ __('general.signup') }}"
                    x-ref="comment"
                    wire:click.prevent="redirectToRegister"
                />
            </div>
        </div>
    @endauth
</div>
