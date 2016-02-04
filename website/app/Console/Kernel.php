<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        Commands\StripeNormalizeMetadata::class,
        Commands\StripeNormalizePaymentFees::class,
        Commands\StripeNormalizePaymentRefundOrders::class,
        Commands\StripeNormalizePaymentSubscriptions::class,
        Commands\OrderNormalizeUnityAndFeesPrice::class,
        Commands\BillingNormalizeMasterbox::class,
        Commands\QuestionsNormalizeMasterbox::class,
        Commands\BlogNormalizeArticles::class,
        Commands\ContentNormalizePages::class,
        Commands\CoordinateRefresh::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
