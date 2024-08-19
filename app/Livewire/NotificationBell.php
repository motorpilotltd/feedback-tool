<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Idea;
use App\Traits\Livewire\WithDispatchNotify;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class NotificationBell extends Component
{
    use WithDispatchNotify;

    const NOTICATION_THRESHOLD = 20;

    public $notifications;

    public $isLoading;

    public $notificationCount;

    protected $listeners = ['getNotifications'];

    public function mount()
    {
        $this->notifications = collect([]);
        $this->isLoading = true;
        $this->getNotificationCount();
    }

    public function getNotificationCount()
    {
        $this->notificationCount = auth()->user()->unreadNotifications()->count();

        if ($this->notificationCount > self::NOTICATION_THRESHOLD) {
            $this->notificationCount = self::NOTICATION_THRESHOLD.'+';
        }
    }

    public function getNotifications()
    {
        $this->notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(self::NOTICATION_THRESHOLD)
            ->get();
        $this->isLoading = false;
    }

    public function markAsRead($notificationId)
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $notification = DatabaseNotification::findOrFail($notificationId);
        // or markAsRead to flag read_at instead of deleting from table
        $notification->delete(); // Or delete completely

        switch ($notification->type) {
            case 'comment':
                $this->scrollToComment($notification);
                break;
            case 'idea':
                $this->goToIdea($notification);
                break;
        }
    }

    public function scrollToComment($notification)
    {
        $idea = Idea::find($notification->data['idea_id']);
        if (! $idea) {
            $this->sessionNotify('warning', __('text.idea_not_exist'));

            return redirect()->route('product.index');
        }

        $comment = Comment::find($notification->data['comment_id']);
        if (! $comment) {
            $this->sessionNotify('warning', __('The comment no longer exists!'));

            return redirect()->route('idea.show', [
                'idea' => $notification->data['idea_slug'],
            ]);
        }

        $comments = $idea->comments()->pluck('id');
        $indexOfComment = $comments->search($comment->id);

        $page = (int) ($indexOfComment / $comment->getPerPage()) + 1;

        session()->flash('scrollToComment', $comment->id);

        return redirect()->route('idea.show', [
            'idea' => $notification->data['idea_slug'],
            'page' => $page,
        ]);
    }

    public function goToIdea($notification)
    {
        $idea = Idea::find($notification->data['idea_id']);
        if (! $idea) {
            $this->sessionNotify('warning', __('text.idea_not_exist'));

            return redirect()->route('product.index');
        }

        return redirect()->route('idea.show', [
            'idea' => $notification->data['idea_slug'],
        ]);
    }

    public function markAllAsRead()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        // or markAsRead to flag read_at instead of deleting from table
        auth()->user()->unreadNotifications->markAsRead();
        $this->getNotificationCount();
        $this->getNotifications();
    }

    public function render()
    {
        return view('livewire.notification-bell', [
            'notifications' => auth()->user()->unreadNotifications,
        ]);
    }
}
