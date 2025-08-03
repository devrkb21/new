<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function envelope()
    {
        return new Envelope(
            from: $this->details['email'],
            subject: 'New Contact Form Message from ' . $this->details['name'],
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.contact.form',
        );
    }

    public function attachments()
    {
        return [];
    }
}