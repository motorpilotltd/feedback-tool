<?php

namespace App\Notifications;

use App\Settings\GeneralSettings;
use App\Traits\WithCustomNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IdeaAdded extends Notification implements ShouldQueue
{
    use Queueable, WithCustomNotification;

    const NOTIFICATION_TYPE = 'idea';

    public $idea;

    public $onBehalf;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($idea, $onBehalf = false)
    {
        $this->idea = $idea;
        $this->onBehalf = $onBehalf;
        $this->customType = 'idea';
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

        if (! $this->onBehalf) {
            return (new MailMessage)
                ->subject(config('app.name', 'Feedback App').': An new idea added to product - '.$this->idea->product->name)
                ->markdown('emails.idea-added', [
                    'idea' => $this->idea,
                ]);
        } else {
            return (new MailMessage)
                ->subject(config('app.name', 'Feedback App').': An idea has been added on your behalf')
                ->markdown('emails.idea-added-onbehalf', [
                    'idea' => $this->idea,
                ]);
        }

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
            'idea_id' => $this->idea->id,
            'user_avatar' => $this->idea->addedBy->getAvatar(),
            'user_name' => $this->idea->addedBy->name,
            'idea_slug' => $this->idea->slug,
            'idea_title' => $this->idea->title,
            'product_title' => $this->idea->product->name,
            'on_behalf' => $this->onBehalf,
        ];
    }
}
