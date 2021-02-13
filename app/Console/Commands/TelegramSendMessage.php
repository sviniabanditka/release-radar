<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramSendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:send';

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
        $text = "WHAT\`S NEW 14.02\n";
        $text .= "Added:\n";
        $text .= "- Custom notifications period\n";
        $text .= "- Select notifications types\n";
        $text .= "Fixed:\n";
        $text .= "- Toggling artist notifiable status\n";
        $text .= "- Server timezone\n";
        $text .= "- Release duplicates in notifications\n";
        $text .= "Other:\n";
        $text .= "- Experiments with dashboard UI\n";
        $text .= "- Spotify API improvements\n";
        $text .= "- Other minor fixes&improvements";


        $users = User::query()->whereNotNull('telegram_chat_id')->get();
        foreach ($users as $user) {
            Telegram::sendMessage([
                'chat_id' => $user->telegram_chat_id,
                'text' => $text,
                'parse_mode' => 'markdown',
            ]);
        }
        return 0;
    }
}
