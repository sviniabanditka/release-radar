<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramNotification extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    protected $table = 'telegram_notifications';

    protected $fillable = [
        'user_id',
        'release_id',
        'key',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function release()
    {
        return $this->belongsTo('App\Models\SpotifyRelease', 'release_id', 'id');
    }
}
