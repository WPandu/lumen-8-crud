<?php

namespace App\Console;

use App\Console\Commands\SyncPurchaseOrder;
use App\Console\Commands\SyncPurchaseRequest;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SyncPurchaseRequest::class,
        SyncPurchaseOrder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    //phpcs:ignore
    protected function schedule(Schedule $schedule)
    {
        //noop
    }
}
