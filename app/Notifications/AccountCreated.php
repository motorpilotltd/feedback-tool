<?php

namespace App\Notifications;

use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, ?string $password = null)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
    {
        return ['mail'];
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
            ->subject(config('app.name', 'Feedback App').': '.$this->user->email.' account created')
            ->markdown('emails.account-created', [
                'user' => $this->user,
                'password' => $this->password,
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
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
        ];
    }
}
