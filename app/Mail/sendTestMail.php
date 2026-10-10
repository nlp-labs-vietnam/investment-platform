<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;


class sendTestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $from_email;
    public $site_title;
    public $subject;
    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct($email_from, $subject, $message, $fromName = null)
    {
        $basic = basicControl();
        $this->from_email = $email_from;
        $this->site_title = ($fromName) ?: $basic->site_title;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->from_email,
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.test_email',
            with: [
                'msg' => $this->message
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
