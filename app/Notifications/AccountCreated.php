<?php

namespace App\Notifications;

use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification
{
    use Queueable;

    public $user;

    public $generalSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->generalSettings = resolve(GeneralSettings::class);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        // Send emails to divert test email if enabled
        if ($this->generalSettings->enable_divert_email && ! empty($this->generalSettings->divert_email)) {
            // Modify the notifiable's email address
            $notifiable->email = $this->generalSettings->divert_email;
        }

        return (new MailMessage)
            ->subject(config('app.name', 'Feedback App').': '.$this->user->email.' account created')
            ->markdown('emails.account-created', [
                'user' => $this->user,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
        ];
    }
}
