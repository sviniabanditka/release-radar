<?php

namespace App\Jobs;

use App\Models\SpotifyArtist;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;

class FetchUserSpotifyFollowingList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $log;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->log = Log::channel('spotify_artists');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->log->info('START_FETCH_ARTISTS_LIST_BY_USER', ['user' => $this->user]);
        $session = new Session(env('SPOTIFY_CLIENT_ID'), env('SPOTIFY_CLIENT_SECRET'));
        $session->refreshAccessToken($this->user->spotify_refresh_token);
        $api = new SpotifyWebAPI(['auto_refresh' => true], $session);
        $api->setSession($session);
        try {
            Sentinel::update($this->user, ['spotify_access_token' => $session->getAccessToken(), 'spotify_refresh_token' => $session->getRefreshToken()]);
            $after = null;
            $ids = [];
            do {
                $options = [];
                $options['limit'] = 50;
                if (!empty($after)) {
                    $options['after'] = $after;
                }
                $following_list = $api->getUserFollowedArtists($options);
                if (!empty($following_list->artists->items)) {
                    foreach ($following_list->artists->items as $item) {
                        if (!empty($item->id) && !empty($item->uri)) {
                            $artist = SpotifyArtist::query()->updateOrCreate([
                                'spotify_id' => $item->id,
                                'spotify_uri' => $item->uri,
                            ], [
                                'name' => $item->name,
                                'spotify_url' => $item->external_urls->spotify ?? '',
                                'spotify_data' => $item,
                                'last_synced_at' => Carbon::now(),
                            ]);
                            DB::table('user_spotify_artists')->updateOrInsert([
                                'user_id' => $this->user->id,
                                'artist_id' => $artist->id,
                            ]);
                            $ids[] = $artist->id;
                        }
                    }
                }
                if (!empty($following_list->artists->next) && !empty($following_list->artists->cursors->after)) {
                    $after = $following_list->artists->cursors->after;
                }
            } while (!empty($following_list->artists->next) && !empty($following_list->artists->cursors->after));
            if (!empty($ids)) {
                DB::table('user_spotify_artists')->where('user_id', $this->user->id)->whereNotIn('artist_id', $ids)->delete();
            }

            $this->log->info('FINISH_FETCH_ARTISTS_LIST_BY_USER', ['user' => $this->user, 'artists_ids' => $ids]);
        } catch (SpotifyWebAPIException $e) {
            if ($e->getCode() == 429) { // 429 is Too Many Requests
                $lastResponse = $api->getRequest()->getLastResponse();
                $retryAfter = $lastResponse['headers']['retry-after']; // Number of seconds to wait before sending another request
                $this->log->warning('FETCH_TOO_MANY_REQUESTS', ['retryAfter' => $retryAfter, 'error' => $e]);
                sleep($retryAfter+20);
                $this->handle();
            }
        }
    }
}
