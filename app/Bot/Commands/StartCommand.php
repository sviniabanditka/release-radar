<?php

namespace App\Bot\Commands;

use App\Bot\Helpers\BotHelper;
use App\Models\Client;
use App\Models\ClientStep;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Start Command to get you started";

    protected $log;

    public function __construct()
    {
        $this->log = Log::channel('telegram_bot');
    }

    /**
     * Handle command
     */
    public function handle()
    {
        $update = $this->getUpdate();
        $message = $update->getMessage();
        $chat = $update->getChat();
        $code = str_replace('/start', '', $message->text);
        $this->log->info('TELEGRAM_START_COMMAND', ['chat_id' => $chat->id, 'code' => $code, 'text' => $message->text]);
        if ($code && $code = trim($code)) {
            $user = User::query()->whereNull('telegram_chat_id')->where('telegram_temp_code', $code)->first();
            $this->log->info('TELEGRAM_START_COMMAND_USER', ['chat_id' => $chat->id, 'user_id' => $user->id ?? null, 'email' => $user->email ?? null]);
            if ($user) {
                $user->telegram_chat_id = $chat->id;
                $user->telegram_temp_code = null;
                $user->save();
                $this->replyWithMessage([
                    'text' => 'Congrats! You successfully linked telegram to your ReleaseRadar account!',
                    'parse_mode' => 'markdown',
                ]);
            }
        }
    }
}
