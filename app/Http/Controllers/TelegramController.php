<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function getToggleTelegramStatus()
    {
        $user = Sentinel::getUser();
        if ($user) {
            if (!empty($user->telegram_chat_id)) {
                if (Sentinel::update($user, ['telegram_chat_id' => null, 'telegram_temp_code' => null])) {
                    toastr('Telegram successfully disabled');
                    return redirect()->route('dashboard.get');
                }
            } else {
                $code = Str::random();
                if (Sentinel::update($user, ['telegram_chat_id' => null, 'telegram_temp_code' => $code])) {
                    return redirect(env('TELEGRAM_BOT_URL').'?start='.$code.'');
                }
            }
        }
    }

    public function setWebhook()
    {
        $response = Telegram::setWebhook(['url' => env('TELEGRAM_BOT_WEBHOOK')]);
        if ($response) {
            toastr('Webhook set successfully.');
        } else {
            toastr('Webhook setting failed', 'error');
        }
        return redirect()->route('dashboard.get');
    }

    public function getUpdates()
    {
        Log::warning('TEST__QQ');
        $update = Telegram::commandsHandler(true);
        return 'ok';
    }
}
