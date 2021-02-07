<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyArtist extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'spotify_id',
        'spotify_uri',
        'spotify_url',
        'spotify_data',
        'last_synced_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'spotify_data' => 'array'
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User','user_spotify_artists','artist_id', 'user_id');
    }

    public function releases()
    {
        return $this->hasMany('App\Models\SpotifyRelease', 'artist_id', 'id');
    }
}
