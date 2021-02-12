<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSpotifyArtist extends Model
{
    protected $table = 'user_spotify_artists';

    protected $fillable = [
        'id',
        'artist_id',
        'user_id',
        'is_active'
    ];
}
