<?php

namespace App\Notifications;

use App\Settings\GeneralSettings;
use App\Traits\WithCustomNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IdeaAdded extends Notification
{
    use Queueable, WithCustomNotification;

    const NOTIFICATION_TYPE = 'idea';

    public $idea;

    public $onBehalf;

    public $generalSettings;

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
        $this->generalSettings = resolve(GeneralSettings::class);
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
        // Send emails to divert test email if enabled
        if ($this->generalSettings->enable_divert_email && ! empty($this->generalSettings->divert_email)) {
            // Modify the notifiable's email address
            $notifiable->email = $this->generalSettings->divert_email;
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
