<?php

namespace App\Console\Commands;

use App\Jobs\FetchUserSpotifyFollowingList;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncUserSpotifyFollowingArtists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spotify:sync_following';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users Spotify Following Artists lists';

    protected $log;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->log = Log::channel('spotify_artists');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->log->info('START_SYNC_ARTISTS_COMMAND');
        $users = User::query()->whereNotNull(['spotify_access_token', 'spotify_refresh_token'])->get();
        foreach ($users as $user) {
            FetchUserSpotifyFollowingList::dispatch($user);
        }
        $this->log->info('FINISH_SYNC_ARTISTS_COMMAND');
        return 0;
    }
}
