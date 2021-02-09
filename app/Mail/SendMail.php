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
        return $this->view('emails/send_mail')
            ->from($this->data['from'])
            ->subject($this->data['subject'])
            ->attach(
                $this->data['attachments']->getRealPath(),
                [
                    'as' => $this->data['attachments']->getClientOriginalName(),
                    'mime' => $this->data['attachments']->getClientMimeType(),
                ]
            );
    }
}
