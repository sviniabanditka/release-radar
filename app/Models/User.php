<?php

namespace App\Models;

use Carbon\Carbon;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Support\Arr;

class User extends EloquentUser
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
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
        'last_notified',
        'telegram_notifications_period',
        'telegram_notifications_types',
        'telegram_notifications_format'
    ];

    protected $casts = [
        'permissions' => 'json',
        'spotify_data' => 'array',
        'telegram_notifications_period' => 'array',
        'telegram_notifications_types' => 'array',
    ];

    public function spotify_artists()
    {
        return $this->belongsToMany('App\Models\SpotifyArtist','user_spotify_artists','user_id', 'artist_id')
            ->withPivot('is_active');
    }

    public function getAllowedNotificationsTypes()
    {
        $allowed_types = [];
        foreach ($this->telegram_notifications_types as $key => $value) {
            if ($value == 1) {
                $allowed_types[] = $key;
            }
        }
        return $allowed_types;
    }

    public function getNextTelegramNotificationTime()
    {
        $period = $this->telegram_notifications_period;
        $period_time = $period['time'] ?? 12;
        $date = Carbon::today()->addHours(12)->lessThanOrEqualTo(Carbon::now()) ? Carbon::today()->addHours(12) : Carbon::tomorrow()->addHours(12);
        if ($period['type'] == 'day') {
            $date = Carbon::today()->addHours($period_time);
            if ($date->lessThan(Carbon::now()) && $date->diffInMinutes(Carbon::now()) > 20) {
                $date = $date->addDay();
            }
        } elseif (!empty($period['day']) || (isset($period['day']) && $period['day'] == 0)) {
            $date = Carbon::today()->startOfWeek(0)->addDays($period['day'])->addHours($period_time);
            if($date->lessThan(Carbon::now()) && $date->diffInMinutes(Carbon::now()) > 20) {
                $date = $date->addWeek();
            }
        }
        return $date;
    }

    public function telegram_notifications()
    {
        return $this->hasMany('App\Models\TelegramNotification', 'user_id', 'id');
    }


    public function getReleaseTextByFormat(SpotifyRelease $release)
    {
        $format = $this->telegram_notifications_format;
        preg_match_all('@\[\[\[\[.*?\]\]\]\]@', $format, $matches);
        if (!empty($matches[0])) {
            $release_text = $format;
            foreach ($matches[0] as $tag) {
                $json = \Illuminate\Support\Str::replaceFirst(']]]]', '', \Illuminate\Support\Str::replaceFirst('[[[[', '', $tag));
                $decoded = json_decode($json, true);
                $replace = '';
                if (!empty($decoded['key'])) {
                    switch ($decoded['key']) {
                        case 'artist_name':
                            $replace = $release->artist->name;
                            break;
                        case 'artist_name_link':
                            $replace = '<a href="'.$release->artist->spotify_url.'">'.$release->artist->name.'</a>';
                            break;
                        case 'release_name':
                            $replace = $release->name;
                            break;
                        case 'release_name_link':
                            $replace = '<a href="'.$release->spotify_url.'">'.$release->name.'</a>';
                            break;
                        case 'release_date':
                            $replace = $release->release_date;
                            break;
                        case 'release_artists_list':
                            $replace = '';
                            foreach($release->artists as $art) {
                                if (!empty($art['name'])) {
                                    if (!empty($art['external_urls']['spotify'])) {
                                        $replace .= '<a href="'.$art['external_urls']['spotify'] .'">'.$art['name'] .'</a>';
                                    } else {
                                        $replace .= $art['name'];
                                    }
                                    if ($art != Arr::last($release->artists)) {
                                        $replace .= ', ';
                                    }
                                }
                            }
                            break;
                        case 'release_type':
                            $replace = \App\Models\SpotifyRelease::$TYPES[$release->album_group] ?? '';
                            break;
                        case 'artist_uri':
                            $replace = $release->artist->spotify_uri;
                            break;
                        case 'artist_uri_link':
                            $replace = '<a href="'.$release->artist->spotify_url.'">'.$release->artist->spotify_uri.'</a>';
                            break;
                        case 'release_uri':
                            $replace = $release->spotify_uri;
                            break;
                        case 'release_uri_link':
                            $replace = '<a href="'.$release->spotify_url.'">'.$release->spotify_uri.'</a>';
                            break;
                        case 'artist_id':
                            $replace = $release->artist->spotify_id;
                            break;
                        case 'artist_id_link':
                            $replace = '<a href="'.$release->artist->spotify_url.'">'.$release->artist->spotify_id.'</a>';
                            break;
                        case 'release_id':
                            $replace = $release->spotify_id;
                            break;
                        case 'release_id_link':
                            $replace = '<a href="'.$release->spotify_url.'">'.$release->spotify_id.'</a>';
                            break;
                        case 'artist_url':
                            $replace = $release->artist->spotify_url;
                            break;
                        case 'release_url':
                            $replace = $release->spotify_url;
                            break;
                    }
                }
                $release_text = \Illuminate\Support\Str::replaceFirst($tag, $replace, $release_text);
            }
            $text = $release_text;
        } else {
            $text = '<a href="' . $release->artist->spotify_url . '">' . $release->artist->name . '</a> - <a href="' . $release->spotify_url . '">' . $release->name . '</a>';
        }
        return $text;
    }
}
