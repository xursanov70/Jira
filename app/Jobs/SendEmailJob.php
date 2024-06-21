<?php

namespace App\Jobs;

use App\Mail\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $confirmCode;
    public function __construct($confirmCode)
    {
        $this->confirmCode = $confirmCode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->confirmCode['email'])->send(new Message($this->confirmCode['code']));
    }
}
