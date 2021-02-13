<?php

namespace App\Console\Commands;

use App\Models\SpotifyRelease;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class OneTimeUpdateReleases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oneTime:releases';

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
        $releases = SpotifyRelease::all();
        $progressBar = $this->output->createProgressBar(count($releases));
        foreach ($releases as $release) {
            if (!empty($release->spotify_data)) {
                $spotify_data = $release->spotify_data;
                $image = !empty($spotify_data['images']) ? Arr::first($spotify_data['images'])['url'] : null;
                $release->album_group = $spotify_data['album_group'] ?? null;
                $release->album_type = $spotify_data['album_type'] ?? null;
                $release->artists = $spotify_data['artists'] ?? null;
                $release->cover = $image;
                $release->save();
                $progressBar->advance();
            }
        }
        $progressBar->finish();
        return 0;
    }
}
