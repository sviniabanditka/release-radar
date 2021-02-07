<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SpotifySyncAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spotify:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync following lists & releases by artists list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('spotify:sync_following');
        Artisan::call('spotify:sync_releases');
        return 0;
    }
}
