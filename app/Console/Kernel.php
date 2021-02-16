<?php

namespace App\Console;

use App\Console\Commands\SpotifySyncAll;
use App\Console\Commands\SyncSpotifyReleases;
use App\Console\Commands\SyncUserSpotifyFollowingArtists;
use App\Console\Commands\TelegramNotifyUsers;
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
        SyncUserSpotifyFollowingArtists::class,
        SyncSpotifyReleases::class,
        SpotifySyncAll::class,
        TelegramNotifyUsers::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('spotify:sync_following')->daily()->at('00:00');
        $schedule->command('spotify:sync_releases')->daily()->at('01:00');
        $schedule->command('telegram:notify')->hourly();

        //BACKUPS
        $schedule->command('backup:clean')->daily()->at('04:00');
        $schedule->command('backup:run')->daily()->at('05:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
