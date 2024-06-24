<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTaskMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    
   public $taskMessage;

   /**
    * Create a new message instance.
    *
    * @param string $taskMessage
    * @return void
    */
   public function __construct($taskMessage)
   {
       $this->taskMessage = $taskMessage;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
       return $this->view('mail.sendTask')
                   ->with('taskMessage', $this->taskMessage);
   }
}
