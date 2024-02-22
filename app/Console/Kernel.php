<?php

namespace App\Console;

use App\Console\Commands\DeleteExpiredConfirmCodes;
use App\Console\Commands\DeleteExpiredEmailCodes;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\Heartbeat;
use App\Models\ConfirmCode;
use Carbon\Carbon;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $signature = 'delete:expired_email_codes';
    protected $description = 'Delete expired email codes from the ConfirmCode table';

    protected function schedule(Schedule $schedule)
    {
        $expirationTime = Carbon::now()->subSeconds(10);
        
        $schedule->call(function () {
            ConfirmCode::where('created_at', '<', Carbon::now()->subSeconds(10))->delete();
        })->subMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}