<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'spotify_id',
        'spotify_uri',
        'spotify_url',
        'spotify_data',
        'release_date',
        'created_at',
        'updated_at',
        'artist_id',
        'last_updated'
    ];

    protected $casts = [
        'spotify_data' => 'array'
    ];

    public function artist()
    {
        return $this->belongsTo('App\Models\SpotifyArtist','artist_id','id');
    }
}
