<?php

namespace App\Livewire\Idea;

use App\Models\Comment as CommentModel;
use App\Models\Idea;
use App\Services\Comment\CommentSpamService;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Comment extends Component
{
    use WireUiActions;

    public $comment;

    public $parentIdea;

    public $authUser;

    public $isPinned;

    public $pinnedCommentId;

    protected function getListeners()
    {
        return [
            "comment:refresh:{$this->comment->id}" => 'refreshComment',
        ];
    }

    public function mount(CommentModel $comment, Idea $parentIdea, $isPinned = false)
    {
        $this->comment = $comment;
        $this->parentIdea = $parentIdea;
        $this->authUser = auth()->user();
        $this->isPinned = $isPinned;
        $this->pinnedCommentId = $parentIdea->sticky_comment_id;
    }

    public function deleteConfirm(CommentModel $comment, bool $confirm = false)
    {
        if (auth()->guest() || auth()->user()->cannot('delete', $comment)) {
            $this->notification()->warning(
                $description = __('error.actionnotpermitted'),
            );
        } else {
            if (! $confirm) {
                $this->dialog()->confirm([
                    'title' => __('text.areyousure'),
                    'description' => __('text.deletethiscommentconfirm'),
                    'accept' => [
                        'label' => __('text.yes_confirm'),
                        'method' => 'deleteConfirm',
                        'params' => [
                            $comment,
                            true,
                        ],
                    ],
                ]);
            } else {
                CommentModel::destroy($comment->id);
                CommentModel::make();
                $this->notification()->success(
                    $description = __('text.commentdeletedsuccess'),
                );
                $this->dispatch('commentWasDeleted');
                $this->dispatch('refreshIdeaShow');
            }
        }
    }

    public function markSpamConfirm(CommentSpamService $commentSpamService, CommentModel $comment, bool $hasMarkedSpam, bool $confirm = false)
    {
        if (auth()->guest()) {
            $this->notification()->warning(
                $description = __('error.actionnotpermitted'),
            );
        } else {
            if (! $confirm) {
                $this->dialog()->confirm([
                    'icon' => 'exclamation-triangle',
                    'title' => $hasMarkedSpam ? __('text.unmarkcommentspam') : __('text.markcommentspam'),
                    'description' => $hasMarkedSpam ? __('text.unmarksommentspamconfirm') : __('text.marksommentspamconfirm'),
                    'accept' => [
                        'label' => __('text.yes_confirm'),
                        'method' => 'markSpamConfirm',
                        'params' => [
                            $comment,
                            $hasMarkedSpam,
                            true,
                        ],
                    ],
                ]);
            } else {
                $commentSpamService->toggleSpam($comment, auth()->user());
                $message = $hasMarkedSpam ? __('text.commentunmarkedspamsuccess') : __('text.commentmarkedspamsuccess');
                $this->notification()->success(
                    $description = $message,
                );

                $this->dispatch('commentWasMarkedAsSpam');
                $this->dispatch("comment:refresh:{$comment->id}");
            }
        }
    }

    public function commentNotSpamConfirm(CommentModel $comment, bool $confirm = false)
    {
        if (auth()->guest()) {
            $this->notification()->warning(
                $description = __('error.actionnotpermitted'),
            );
        } else {
            if (! $confirm) {
                $this->dialog()->confirm([
                    'icon' => 'exclamation-triangle',
                    'title' => __('text.commentnotspam'),
                    'description' => __('text.commentremovespamconfirm'),
                    'accept' => [
                        'label' => __('text.yes_confirm'),
                        'method' => 'commentNotSpamConfirm',
                        'params' => [
                            $comment,
                            true,
                        ],
                    ],
                ]);
            } else {
                $comment->spams()->sync([]);
                $this->notification()->success(
                    $description = __('text.removespamsuccess'),
                );
                $this->dispatch('commentMarkedAsNotSpam');
                $this->dispatch("comment:refresh:{$comment->id}");
            }
        }
    }

    public function pinningComment(int $commentId, bool $isPinned)
    {
        $idea = $this->parentIdea;
        $prevPinned = $idea->sticky_comment_id;
        $idea->sticky_comment_id = ! $isPinned ? $commentId : 0;
        $this->pinnedCommentId = $idea->sticky_comment_id;
        $idea->save();
        $this->dispatch('refreshIdeaShow');
        $this->dispatch("comment:refresh:{$prevPinned}");
        if (! $isPinned) {
            $this->dispatch('scroll-to-pinned-comment', true);
        }
    }

    public function refreshComment()
    {
        $idea = Idea::select('sticky_comment_id')->find($this->comment->idea_id);
        $this->pinnedCommentId = $idea->sticky_comment_id;
        $this->comment = CommentModel::with(['spams'])
            ->where('id', $this->comment->id)
            ->get()
            ->first();
    }

    public function render()
    {
        return view('livewire.idea.comment');
    }
}
