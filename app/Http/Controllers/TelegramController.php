<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
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
        Telegram::commandsHandler(true);
        return 'ok';
    }

    public function postUpdateSettings()
    {
        $data = [];
        if (request()->has('telegram_notifications_period')) {
            $period = request()->get('telegram_notifications_period');
            $data['telegram_notifications_period'] = [
                'type' => $period['type'] ?? 'day',
                'day' => $period['day'] ?? 0,
                'time' => $period['time'] ?? 12,
            ];
        }
        if (request()->has('telegram_notifications_types')) {
            $types = request()->get('telegram_notifications_types');
            $data['telegram_notifications_types'] = [
                'album' => !empty($types['album']) ? 1 : 0,
                'single' => !empty($types['single']) ? 1 : 0,
                'appears_on' => !empty($types['appears_on']) ? 1 : 0,
                'compilation' => !empty($types['compilation']) ? 1 : 0,
            ];
        }
        $data['telegram_notifications_format'] = request()->get('telegram_notifications_format') ?? null;
        $user = Sentinel::getUser();
        if ($user) {
            if(Sentinel::update($user, $data)) {
                toastr('Telegram settings successfully updated');
            } else {
                toastr('Update Telegram settings error', 'error');
            }
        } else {
            toastr('Unauthorized', 'error');
        }
        return redirect()->to(url()->previous().'#telegram');
    }
}
