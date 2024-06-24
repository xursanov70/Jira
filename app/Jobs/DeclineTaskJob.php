<?php

namespace App\Jobs;

use App\Mail\DeclineTaskMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class DeclineTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $taskMessage;
    public $user;
    public function __construct($taskMessage, $user)
    {
        $this->taskMessage = $taskMessage;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user['email'])->send(new DeclineTaskMail($this->taskMessage));
    }
}
