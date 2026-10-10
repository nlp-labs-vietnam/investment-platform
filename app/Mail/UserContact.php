<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserContact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->details['sub'])
            ->replyTo($this->details['replyToEmail'], $this->details['replyToName'])
            ->from(basicControl()->sender_email, basicControl()->sender_email_name)
            ->view('layouts.mail',[
                'msg' => $this->details['message'],
            ]);
    }
}
