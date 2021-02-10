<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $attachments = $this->data['attachments'];

        $message = $this->view('emails/send_mail')
            ->from($this->data['from'])
            ->subject($this->data['subject']);

        foreach ($attachments as $attachment) {
            $message->attach(
                $attachment->getRealPath(),
                [
                    'as' => $attachment->getClientOriginalName(),
                    'mime' => $attachment->getClientMimeType(),
                ]
            );
        }
        return $message;
    }
}
