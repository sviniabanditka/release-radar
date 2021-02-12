<?php

namespace App\Console\Commands;

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

    protected $log;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->log = Log::channel('telegram_notifications');
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
            $user_artists_ids = DB::table('user_spotify_artists')->where('user_id', $user->id)->where('is_active', 1)->get();
            $releases = SpotifyRelease::query()
                ->whereIn('artist_id', $user_artists_ids->pluck('artist_id')->toArray())
                ->whereBetween('release_date', [Carbon::yesterday(), Carbon::today()->subMinutes(1)])
                ->orderBy('artist_id')
                ->get();
            if (!empty($releases) && count($releases) > 0) {
                $chunked_releases = $releases->chunk(10);
                foreach ($chunked_releases as $chunk) {
                    if (!empty($chunk) && count($chunk) > 0) {
                        $text = 'Your new yesterday releases:'.PHP_EOL.PHP_EOL;
                        foreach ($chunk as $release) {
                            $text .= '<a href="' . $release->artist->spotify_url . '">' . $release->artist->name . '</a> - <a href="' . $release->spotify_url . '">' . $release->name . '</a>' . PHP_EOL . PHP_EOL;
                        }
                        Telegram::sendMessage([
                            'chat_id' => $user->telegram_chat_id,
                            'text' => $text,
                            'parse_mode' => 'HTML',
                        ]);
                    }
                }
                $this->log->info('NOTIFY_TELEGRAM_USER', ['user_email' => $user->email, 'releases' => $releases->pluck('spotify_id')->toArray()]);
            }
            $user->last_notified = Carbon::now();
            $user->save();
        }
        return 0;
    }
}
