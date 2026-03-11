<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->user->send_subject, // Now correctly assigned in controller
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.send_mail', // Fix: Removed leading space
            with: [
                'user' => $this->user,
            ],
        );
    }
}