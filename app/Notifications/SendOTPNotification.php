<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOTPNotification extends Notification
{
    use Queueable;

    protected $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail']; // Use 'mail' for sending OTP via email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your OTP is: ' . $this->otp)
                    ->line('This OTP will expire in 10 minutes.')
                    ->line('If you did not request this OTP, please ignore this email.');
    }
}
