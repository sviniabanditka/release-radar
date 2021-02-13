<?php

namespace App\Models;

use Carbon\Carbon;
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
        'last_notified',
        'telegram_notifications_period',
        'telegram_notifications_types',
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
}
