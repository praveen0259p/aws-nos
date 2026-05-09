<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeUser extends Notification
{
    use Queueable;
    public function __construct()
    {
        //
    }
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('Welcome to Our Website!')->view('emails.welcome-user', ['user' => $notifiable]);
    }
}
