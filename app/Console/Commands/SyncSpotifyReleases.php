<?php

namespace App\Console\Commands;

use App\Models\SpotifyArtist;
use App\Models\SpotifyRelease;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;

class SyncSpotifyReleases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spotify:sync_releases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Spotify releases by artists list';

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
        $users = User::query()->whereNotNull(['spotify_access_token', 'spotify_refresh_token'])->get();
        $artists = [];
        $artists_by_user = [];
        foreach ($users as $user) {
            foreach ($user->spotify_artists()->where('is_active', 1)->get() as $artist) {
                if (!in_array($artist->spotify_id, $artists)) {
                    $artists[] = $artist->spotify_id;
                    $artists_by_user[$user->id][] = $artist->spotify_id;
                }
            }
        }
        if (!empty($artists) && !empty($artists_by_user)) {
            foreach ($artists_by_user as $key => $_artists) {
                $user = Sentinel::findById($key);
                if ($user) {
                    $session = new Session(env('SPOTIFY_CLIENT_ID'), env('SPOTIFY_CLIENT_SECRET'));
                    $session->refreshAccessToken($user->spotify_refresh_token);
                    $api = new SpotifyWebAPI(['auto_refresh' => true], $session);
                    $api->setSession($session);
                    try {
                        foreach ($_artists as $artist_id) {
                            $artist = SpotifyArtist::query()->where('spotify_id', $artist_id)->first();
                            //if last artist release sync time < now more than 15 hours, skip this artist
                            $skip = false;
                            if ($artist && $exists = SpotifyRelease::query()->where('artist_id', $artist->id)->orderByDesc('release_date')->first()) {
                                if (Carbon::now()->diffInHours(Carbon::parse($exists->last_updated)) < 15) {
                                    $skip = true;
                                }
                            }

                            if ($artist && !$skip) {
                                $options['limit'] = 50;
                                $options['offset'] = 0;
                                $albums = $api->getArtistAlbums($artist_id, $options);
                                if (!empty($albums->items)) {
                                    foreach ($albums->items as $item) {
                                        if (!empty($item->id) && !empty($item->uri) && !empty($item->release_date)) {
                                            SpotifyRelease::query()->updateOrCreate([
                                                'spotify_id' => $item->id,
                                                'spotify_uri' => $item->uri,
                                            ], [
                                                'name' => $item->name,
                                                'spotify_url' => $item->external_urls->spotify ?? '',
                                                'spotify_data' => $item,
                                                'release_date' => Carbon::parse($item->release_date),
                                                'artist_id' => $artist->id,
                                                'last_updated' => Carbon::now(),
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    } catch (SpotifyWebAPIException $e) {
                        Log::warning('SPOTIFY_EXCEPTION', ['message' => $e->getMessage(), 'code' => $e->getCode(), 'trace' => $e->getTrace()]);
                        if ($e->getCode() == 429) { // 429 is Too Many Requests
                            $lastResponse = $api->getRequest()->getLastResponse();
                            $retryAfter = $lastResponse['headers']['retry-after']; // Number of seconds to wait before sending another request
                            $this->output->warning('429 Too Many Requests. Retrying after '.($retryAfter+20).' seconds');
                            sleep($retryAfter+20);
                            Artisan::call('spotify:sync_releases');
                        }
                    }
                }
            }
        }
        return 0;
    }
}
