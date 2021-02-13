<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class OneTimeStoreNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oneTime:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $users = User::all();
        foreach ($users as $user) {
            $key = Str::random(12);
            if (!empty($user->spotify_artists)) {
                foreach ($user->spotify_artists as $artist) {
                    if (!empty($artist->releases)) {
                        foreach ($artist->releases as $release) {
                            $user->telegram_notifications()->create([
                                'user_id' => $user->id,
                                'release_id' => $release->id,
                                'key' => $key,
                                'created_at' => Carbon::now()
                            ]);
                        }
                    }
                }
            }
        }
        return 0;
    }
}
