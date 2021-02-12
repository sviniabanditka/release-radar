<?php

namespace App\Http\Controllers;

use App\Jobs\FetchUserSpotifyFollowingList;
use App\Models\SpotifyArtist;
use App\Models\UserSpotifyArtist;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session(
            env('SPOTIFY_CLIENT_ID'),
            env('SPOTIFY_CLIENT_SECRET'),
            route('spotify.callback.get')
        );
    }

    public function getToggleSpotifyStatus()
    {
        $user = Sentinel::getUser();
        if ($user) {
            if (!empty($user->spotify_access_token)) {
                if (Sentinel::update($user, ['spotify_access_token' => null])) {
                    toastr('Spotify successfully disabled');
                    return redirect()->route('dashboard.get');
                }
            } else {
                $options = [
                    'scope' => [
                        'user-read-email',
                        'user-follow-read',
                        'user-library-read'
                    ],
                ];
                return redirect($this->session->getAuthorizeUrl($options));
            }
        }
    }

    public function getToggleArtistStatus($id)
    {
        if ($artist = SpotifyArtist::query()->find($id)) {
            if ($user = Sentinel::getUser()) {
                $user_artist = UserSpotifyArtist::query()->where('user_id', $user->id)->where('artist_id', $artist->id)->first();
                if ($user_artist) {
                    $user_artist->is_active = $user_artist->is_active == 1 ? 0 : 1;
                    $user_artist->save();
                    toastr('Artist notifications successfully '.($user_artist->is_active == 1 ? 'enabled' : 'disabled'));
                    return redirect()->back();
                }
            }
        }
        toastr('Error toggling artist status', 'error');
        return redirect()->back();
    }

    public function getSpotifyRedirectUrlCallback(Request $request)
    {
        $user = Sentinel::getUser();
        if($user && !empty($request->get('code'))) {
            $this->session->requestAccessToken($request->get('code'));
            $accessToken = $this->session->getAccessToken();
            $refreshToken = $this->session->getRefreshToken();
            $api = new SpotifyWebAPI();
            $api->setAccessToken($accessToken);
            $me = $api->me();
            if ($accessToken && $refreshToken && $me) {
                Sentinel::update($user, ['spotify_access_token' => $accessToken, 'spotify_refresh_token' => $refreshToken, 'spotify_data' => $me]);
                toastr('Spotify successfully linked');
                FetchUserSpotifyFollowingList::dispatch($user);
                return redirect()->route('dashboard.get');
            }
        }
        toastr('Error linking Spotify to account', 'error');
        return redirect()->route('dashboard.get');
    }

    public function getFollowingList()
    {
        $user = Sentinel::getUser();
        $artists = $user->spotify_artists->sortBy('name');
        return view('following_list', compact('user', 'artists'));
    }

    public function getArtistReleases($artist_id)
    {
        $user = Sentinel::getUser();
        $artist = SpotifyArtist::query()->find($artist_id);
        if ($artist) {
            $releases = $artist->releases->sortByDesc('release_date');
            return view('artist_releases', compact('user', 'artist', 'releases'));
        }
    }

    public function getLatestReleases()
    {
        $user = Sentinel::getUser();
        if ($user) {
            $releases = collect();
            foreach ($user->spotify_artists as $artist) {
                foreach ($artist->releases as $release) {
                    $releases->push($release);
                }
            }
            $releases = $releases->sortByDesc('release_date')->take(50);
            return view('latest_releases', compact('user', 'releases'));
        }
    }
}
