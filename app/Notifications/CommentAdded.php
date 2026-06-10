<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Settings\GeneralSettings;
use App\Traits\WithCustomNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentAdded extends Notification implements ShouldQueue
{
    use Queueable, WithCustomNotification;

    public $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
        $this->customType = 'comment';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        // Resolved here (not in the constructor) so it isn't serialized into the
        // queued job payload and reflects the settings at send time.
        $generalSettings = resolve(GeneralSettings::class);

        // Send emails to divert test email if enabled
        if ($generalSettings->enable_divert_email && ! empty($generalSettings->divert_email)) {
            // Modify the notifiable's email address
            $notifiable->email = $generalSettings->divert_email;
        }

        return (new MailMessage)
            ->subject(config('app.name', 'Feedback App').': A comment was posted on your idea')
            ->markdown('emails.comment-added', [
                'comment' => $this->comment,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'comment_content' => $this->comment->content,
            'user_avatar' => $this->comment->user->getAvatar(),
            'user_name' => $this->comment->user->name,
            'idea_id' => $this->comment->idea->id,
            'idea_slug' => $this->comment->idea->slug,
            'idea_title' => $this->comment->idea->title,
        ];
    }
}
