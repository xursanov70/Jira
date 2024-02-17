<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Message extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $rand)
    {
        $this->rand = $rand;
    }

    public function build()
    {
        return $this->view('mail.message');
    }
}