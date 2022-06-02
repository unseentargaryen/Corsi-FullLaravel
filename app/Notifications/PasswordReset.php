<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordReset extends Notification
{
    use Queueable;

    private string $token;
    private string $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token_param,$email_param)
    {
        $this->token = $token_param;
        $this->email = $email_param;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage())
            ->from("icorsidellatana@gmail.com")
            ->subject("Password Reset")
            ->greeting("Ciao!")
            ->line('è stata richiesta la reimpostazione della password')
            ->action('REIMPOSTA PASSWORD', env("FE_URL").env("FE_RESET_PASSWORD_FORM_URL").$this->token.'/'.$this->email)
            ->salutation("Saluti, I CORSI DELLA TANA")
        ;


        return  $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
