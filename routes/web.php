<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['namespace' => 'App\Http\Controllers'], function() {

    Route::get('/', 'HomeController@showLandingPage')->name('landing.get');
    Route::get('about', 'HomeController@showAboutPage')->name('about.get');

    Route::any('dashboard/bot/telegram/callback', 'TelegramController@getUpdates')->name('telegram.callback.any');

    //AUTH
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', 'AuthController@getLogin')->name('login.get');
        Route::post('login', 'AuthController@postLogin')->name('login.post');

        Route::get('register', 'AuthController@getRegister')->name('register.get');
        Route::post('register', 'AuthController@postRegister')->name('register.post');

        Route::get('forgot', 'AuthController@getForgotPassword')->name('forgot.get');
        Route::post('forgot', 'AuthController@postForgotPassword')->name('forgot.post');

        Route::get('reset', 'AuthController@getResetPassword')->name('reset.get');
        Route::post('reset', 'AuthController@postResetPassword')->name('reset.post');

        Route::get('logout', 'AuthController@getLogout')->name('logout.get');
        Route::get('activate', 'AuthController@getActivateUser')->name('activate.get');

        Route::group(['middleware' => 'role:user'], function () {
            Route::post('email', 'AuthController@postUpdateEmail')->name('email.post');
            Route::post('password', 'AuthController@postUpdatePassword')->name('password.post');
        });
    });

    //DASHBOARD
    Route::group(['middleware' => 'role:user', 'prefix' => 'dashboard'], function () {

        //USER
        Route::group(['middleware' => 'role:user'], function () {
            Route::get('/', 'HomeController@showDashboardPage')->name('dashboard.get');
            Route::get('artists', 'SpotifyController@getFollowingList')->name('following_list.get');
            Route::get('artist/{id}', 'SpotifyController@getArtistReleases')->name('artist_releases.get');
            Route::get('artist/{id}/toggle', 'SpotifyController@getToggleArtistStatus')->name('artist.toggle.get');
            Route::get('releases', 'SpotifyController@getLatestReleases')->name('latest_releases.get');
            Route::get('spotify/toggle', 'SpotifyController@getToggleSpotifyStatus')->name('spotify.toggle.get');
            Route::get('spotify/callback', 'SpotifyController@getSpotifyRedirectUrlCallback')->name('spotify.callback.get');

            Route::get('bot/telegram/toggle', 'TelegramController@getToggleTelegramStatus')->name('telegram.toggle.get');
            Route::post('telegram/update', 'TelegramController@postUpdateSettings')->name('telegram.update.post');
        });

        //ADMIN
        Route::group(['middleware' => 'role:admin'], function () {
            Route::get('bot/telegram/webhook', 'TelegramController@setWebhook')->name('telegram.webhook.get');
        });
    });
});

Route::get('test', function() {
    $user = \Cartalyst\Sentinel\Laravel\Facades\Sentinel::getUser();
    if ($user) {
        $releases = collect();
        foreach ($user->spotify_artists as $artist) {
            foreach ($artist->releases as $release) {
                $releases->push($release);
            }
        }
        $releases = $releases->sortByDesc('release_date')->take(10);
        $text = 'Your new releases:'.PHP_EOL;
        foreach ($releases as $release) {
            $release_text = $user->getReleaseTextByFormat($release);
            if (!empty($release_text)) {
                $text .= $release_text.PHP_EOL;
            }
        }
        dd($text);
    }
    return true;
});
