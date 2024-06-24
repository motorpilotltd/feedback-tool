<?php

namespace App\Livewire\Idea;

use App\Models\Idea;
use App\Models\TagGroup;
use App\Services\Idea\IdeaSpamService;
use App\Services\Idea\IdeaVoteService;
use App\Traits\Livewire\WithDispatchNotify;
use Livewire\Component;
use WireUi\Traits\Actions;

class IdeaShow extends Component
{
    use Actions, WithDispatchNotify;

    public $idea;

    public $product;

    public $ideaTagsByGroup;

    public $votesCount;

    public $hasVoted;

    public $hasTags = false;

    protected $listeners = [
        'refreshIdeaShow' => '$refresh',
        'commentWasDeleted' => '$refresh',
        'setAttachments',
    ];

    public function mount(IdeaVoteService $ideaVoteService, Idea $idea)
    {
        $this->idea = $idea;
        $this->product = $idea->category->product;
        $this->votesCount = $idea->votes()->count();
        $this->hasVoted = $ideaVoteService->isVotedByUser($idea, auth()->user());

        $tagGroups = TagGroup::with('tags')->where('product_id', $idea->productId)->get();
        $ideaTagIds = $idea->tags->pluck('id');
        $this->ideaTagsByGroup = $tagGroups->map(function ($tg) use ($ideaTagIds) {
            $tags = collect([]);
            foreach ($tg->tags as $tag) {
                if ($ideaTagIds->contains($tag->id)) {
                    $tags->push($tag);
                }
            }

            if ($tags->isNotEmpty() && ! $this->hasTags) {
                $this->hasTags = true;
            }
            $tg->tags = $tags;

            return $tg;
        });
    }

    /**
     * Delete the idea confirmation
     *
     * @return void
     */
    public function deleteConfirm(bool $confirm = false): mixed
    {
        if (auth()->guest() || auth()->user()->cannot('delete', $this->idea)) {
            $this->notification()->warning(
                $description = __('error.actionnotpermitted'),
            );
        } else {
            if (! $confirm) {
                $this->dialog()->confirm([
                    'title' => __('text.areyousure'),
                    'description' => __('text.deleteidea'),
                    'accept' => [
                        'label' => __('text.yes_confirm'),
                        'method' => 'deleteConfirm',
                        'params' => [
                            true,
                        ],
                    ],
                ]);
            } else {
                $product = $this->idea->product;

                Idea::destroy($this->idea->id);

                // Display notification when page reloaded
                $this->sessionNotifySuccessDelete(__('text.ideadeletedsuccess'));

                return redirect()->route('product.show', $product);
            }
        }
    }

    /**
     * Mark/Unmark idea spam
     *
     * @return void
     */
    public function ideaSpamConfirm(IdeaSpamService $ideaSpamService, bool $hasMarkedSpam, bool $confirm = false): void
    {
        if (auth()->guest()) {
            $this->notification()->error(
                $title = 'Warning',
                $description = __('error.actionnotpermitted')
            );
        } else {
            if (! $confirm) {
                $this->dialog()->confirm([
                    'icon' => 'exclamation',
                    'title' => __('text.areyousure'),
                    'description' => $hasMarkedSpam ? __('text.unmarkideaspamconfirm') : __('text.markideaspamconfirm'),
                    'accept' => [
                        'label' => __('text.yes_confirm'),
                        'method' => 'ideaSpamConfirm',
                        'params' => [
                            $hasMarkedSpam,
                            true,
                        ],
                    ],
                ]);
            } else {
                $ideaSpamService->toggleSpam($this->idea, auth()->user());

                $this->notification()->success(
                    $description = $hasMarkedSpam ? __('text.ideaunmarkedspamsuccess') : __('text.ideamarkedspamsuccess'),
                );
            }

        }

        $this->dispatch('ideaWasMarkedAsSpam');
        $this->dispatch('refreshIdeaShow');
    }

    /**
     * Clear all spam report
     *
     * @return void
     */
    public function ideaNotSpam(IdeaSpamService $ideaSpamService, bool $confirm = false): void
    {
        if (auth()->guest()) {
            $this->dispatchNotifyWarning(__('error.actionnotpermitted'));
        } else {
            if (! $confirm) {
                $this->dialog()->confirm([
                    'icon' => 'exclamation',
                    'title' => __('text.ideanotspam'),
                    'description' => __('text.idearemovespamconfirm'),
                    'accept' => [
                        'label' => __('text.yes_confirm'),
                        'method' => 'ideaNotSpam',
                        'params' => [
                            true,
                        ],
                    ],
                ]);
            } else {
                $ideaSpamService->clearSpam($this->idea);
                $this->notification()->success(
                    $description = __('text.removespamsuccess'),
                );
            }

        }

        $this->dispatch('ideaMarkedAsNotSpam');
        $this->dispatch('refreshIdeaShow');
    }

    public function render(IdeaSpamService $ideaSpamService)
    {
        return view('livewire.idea.idea-show', [
            'hasMarkedSpam' => (int) $ideaSpamService->userMarkIdeaSpam($this->idea, auth()->user()),
        ]);
    }
}
