<div class="votes-count-button  @if(!$isHorizontal) border-r border-gray-100 @endif">
    @if (!$isResponsive)
        <div class="">
            <!-- Votes count/button view for desktop view-->
            <div class="vote-container hidden @if($isHorizontal) md:flex md:flex-row pr-5 @else md:block px-5 py-8 @endif">
                <div class="text-center">
                    <div class="font-semibold text-2xl @if($hasVoted) text-blue-base @endif @if($isHorizontal) pt-1 leading-none @endif">{{ $votesCount }}</div>
                    <div class="text-gray-500">{{ Str::plural(__('general.vote'), $votesCount) }}</div>
                </div>

                <div class="@if(!$isHorizontal) mt-8 @else ml-2 @endif">
                    @if ($hasVoted)
                        <button
                            wire:click.prevent="voteIdea"
                            type="button"
                            class="w-20 bg-blue-base text-white border border-blue-base hover:bg-blue-hover font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3">Voted</button>
                    @else
                        <button
                            wire:click.prevent="voteIdea"
                            type="button"
                            class="w-20 bg-gray-200 border border-gray-200 hover:border-gray-400 font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3">Vote</button>
                    @endif

                </div>
            </div> <!-- end of vote-container-->
        </div>
    @else
        <!-- Votes count/button view for mobile view-->
        <div class="vote-container flex items-center md:hidden mt-4 md:mt-0">
            <div class="bg-gray-100 text-center rounded-xl h-10 px-4 py-2 pr-8">
                <div class="text-sm font-bold leading-none @if($hasVoted) text-blue-base @endif">{{ $votesCount }}</div>
                <div class="text-xxs font-semibold leading-none text-gray-400">{{ Str::plural(__('general.vote'), $votesCount) }}</div>
            </div>
            @if ($hasVoted)
                <button
                    wire:click.prevent="voteIdea"
                    type="button"
                    class="w-20 bg-blue-base text-white border border-blue-base font-bold text-xxs uppercase rounded-xl hover:bg-blue-hover transition duration-150 ease-in px-4 py-3 -mx-5"
                >
                    Voted
                </button>
            @else
                <button
                    wire:click.prevent="voteIdea"
                    type="button"
                    class="w-20 bg-gray-200 border border-gray-200 font-bold text-xxs uppercase rounded-xl hover:bg-gray-400 transition duration-150 ease-in px-4 py-3 -mx-5"
                >
                    Vote
                </button>
            @endif
        </div> <!-- end of vote-container-->
    @endif
</div>
