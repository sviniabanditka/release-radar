<?php

namespace App\Console\Commands;

use App\Models\SpotifyRelease;
use App\Models\User;
use App\Models\UserSpotifyArtist;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
        $this->log->info('START_NOTIFY_TELEGRAM_USER');
        foreach ($users as $user) {
            $user_next_notification_time = $user->getNextTelegramNotificationTime();
            if ($user_next_notification_time->lessThan(Carbon::now())) {
                $this->log->info('NOTIFY_TELEGRAM_USER', ['user' => $user->email]);
                $user_artists_ids = UserSpotifyArtist::query()->where('user_id', $user->id)->where('is_active', 1)->get();
                $releases = SpotifyRelease::query()
                    ->whereIn('artist_id', $user_artists_ids->pluck('artist_id')->toArray())
                    ->whereIn('album_group', $user->getAllowedNotificationsTypes())
                    ->whereNotIn('id', $user->telegram_notifications->pluck('release_id')->toArray())
                    ->where('release_date', '>', Carbon::today()->subWeek())
                    ->orderBy('artist_id')
                    ->get();
                if (!empty($releases) && count($releases) > 0) {
                    $key = Str::random(12);
                    $chunked_releases = $releases->chunk(5);
                    foreach ($chunked_releases as $chunk) {
                        if (!empty($chunk) && count($chunk) > 0) {
                            $text = 'Your new releases:'.PHP_EOL.PHP_EOL;

                            foreach ($chunk as $release) {
                                $release_text = $user->getReleaseTextByFormat($release);
                                if (!empty($release_text)) {
                                    $text .= $release_text.PHP_EOL.PHP_EOL;
                                }
                                $user->telegram_notifications()->create([
                                    'user_id' => $user->id,
                                    'release_id' => $release->id,
                                    'key' => $key,
                                    'created_at' => Carbon::now()
                                ]);
                            }
                            Telegram::sendMessage([
                                'chat_id' => $user->telegram_chat_id,
                                'text' => $text,
                                'parse_mode' => 'HTML',
                            ]);
                            $user->last_notified = Carbon::now();
                            $user->save();
                        }
                    }
                    $this->log->info('NOTIFY_TELEGRAM_USER', ['user_email' => $user->email, 'releases' => $releases->pluck('spotify_id')->toArray()]);
                }
            }
        }
        $this->log->info('FINISH_NOTIFY_TELEGRAM_USER');
        return 0;
    }
}
