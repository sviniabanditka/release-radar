<?php

namespace App\Console\Commands;

use App\Models\SpotifyArtist;
use App\Models\SpotifyRelease;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramNotifyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::query()->whereNotNull(['spotify_access_token', 'spotify_refresh_token', 'telegram_chat_id'])->get();
        foreach ($users as $user) {
            $date = Carbon::yesterday();
            $artist_ids = DB::table('user_spotify_artists')->where('user_id', $user->id)->where('is_active', 1)->pluck('artist_id')->toArray();
            $releases = SpotifyRelease::query()->whereIn('artist_id', $artist_ids)->where('release_date', '>=', $date)->orderBy('artist_id')->get();
            if (!empty($releases) && count($releases) > 0) {
                $text = 'Your new yesterday releases:'.PHP_EOL.PHP_EOL;
                $tmp = [];
                foreach ($releases as $release) {
                    if ($artist = SpotifyArtist::query()->find($release->artist_id)) {
                        $text .= '<a href="' . $artist->spotify_url . '">' . $artist->name . '</a> - <a href="' . $release->spotify_url . '">' . $release->name . '</a>' . PHP_EOL . PHP_EOL;
                        $tmp[] = $release->id;
                    }
                }
                if (!empty($tmp) && count($tmp) > 0) {
                    Log::warning('NOTIFY_TELEGRAM_USER', ['user_id' => $user->id, 'user_email' => $user->email, 'releases' => $tmp]);
                    Telegram::sendMessage([
                        'chat_id' => $user->telegram_chat_id,
                        'text' => $text,
                        'parse_mode' => 'HTML',
                    ]);
                }
            }
            $user->last_notified = Carbon::now();
            $user->save();
        }
        return 0;
    }
}
