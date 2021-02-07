<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser;

class User extends EloquentUser
{
    protected $fillable = [
        'email',
        'password',
        'last_name',
        'first_name',
        'permissions',
        'spotify_access_token',
        'spotify_refresh_token',
        'spotify_data',
        'telegram_chat_id',
        'telegram_temp_code',
        'last_notified'
    ];

    protected $casts = [
        'permissions' => 'json',
        'spotify_data' => 'array'
    ];

    public function spotify_artists()
    {
        return $this->belongsToMany('App\Models\SpotifyArtist','user_spotify_artists','user_id', 'artist_id');
    }
}
