<?php

namespace App\Http\Controllers;

use App\Bot\Services\CallbackService;
use App\Models\SpotifyRelease;
use App\Models\TelegramNotification;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\Actions;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

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
        return redirect()->back();
    }

    public function getUpdates()
    {
        /** @var Update $update */
        $update = Telegram::commandsHandler(true);
        try {
            $user = User::query()->where('telegram_chat_id', $update->getChat()->id)->first();
            if ($update->isType('callback_query') && $user) {
                //update object is callback
                $callback_data = $update->callbackQuery->data ?? null;
                if ($callback_data) {
                    //Log::info('TELEGRAM_BOT_CALLBACK_DATA', ['action' => $callback_data, 'update' => $update]);
                    //setup tg actions
                    Telegram::sendChatAction(['chat_id' => $user->telegram_chat_id, 'action' => Actions::TYPING]);

                    if (str_contains($callback_data, '/get_current_')) {
                        $serial_data = str_replace('/get_current_', '', $callback_data);
                        $data = unserialize($serial_data);
                        if (!empty($data['current']) && !empty($data['total'])) {
                            $text = 'Page ' . $data['current'] . ' of ' . $data['total'];
                            Telegram::answerCallbackQuery([
                                'callback_query_id' => $update->callbackQuery->id,
                                'show_alert' => true,
                                'text' => $text
                            ]);
                        }
                    } elseif (str_contains($callback_data, '/get_')) {
                        Telegram::answerCallbackQuery(['callback_query_id' => $update->callbackQuery->id]);
                        $serial_data = str_replace('/get_', '', $callback_data);
                        $data = unserialize($serial_data);
                        if (!empty($data['page']) && !empty($data['key'])) {
                            $notifications = TelegramNotification::query()
                                ->where('key', $data['key'])
                                ->where('user_id', $user->id)
                                ->orderByDesc('id')->get();
                            $releases = SpotifyRelease::query()
                                ->whereIn('id', $notifications->pluck('release_id')->toArray())
                                ->orderBy('artist_id')->orderBy('id')->get();
                            $chunked_releases = $releases->chunk(5);
                            foreach ($chunked_releases as $k => $chunk) {
                                if (!empty($chunk) && count($chunk) > 0 && $k == $data['page']-1) {
                                    //make notification text
                                    $text = 'Your new releases:' . PHP_EOL . PHP_EOL;
                                    foreach ($chunk as $release) {
                                        $release_text = $user->getReleaseTextByFormat($release);
                                        if (!empty($release_text)) {
                                            $text .= $release_text . PHP_EOL . PHP_EOL;
                                        }
                                    }

                                    $tg_data = [
                                        'chat_id' => $user->telegram_chat_id,
                                        'text' => $text,
                                        'parse_mode' => 'HTML',
                                        'disable_web_page_preview' => true,
                                    ];
                                    //make keyboard
                                    if (count($chunked_releases) > 1) {
                                        $reply_markup = Keyboard::make()->inline();
                                        $keyboard = [];
                                        if ($data['page'] > 1) {
                                            $keyboard[] = Keyboard::inlineButton(['text' => '<<', 'callback_data' => '/get_' . serialize(['key' => $data['key'], 'page' => $data['page'] - 1])]);
                                        }
                                        $keyboard[] = Keyboard::inlineButton(['text' => $data['page'] . '/' . count($chunked_releases), 'callback_data' => '/get_current_'.serialize(['current' => $data['page'], 'total' => count($chunked_releases)])]);
                                        if (count($chunked_releases) > $data['page']) {
                                            $keyboard[] = Keyboard::inlineButton(['text' => '>>', 'callback_data' => '/get_' . serialize(['key' => $data['key'], 'page' => $data['page'] + 1])]);
                                        }
                                        $reply_markup->row(...$keyboard);
                                        $tg_data['reply_markup'] = $reply_markup;
                                    }
                                    //try to edit or send new message
                                    try {
                                        $n = $notifications->whereNotNull('message_id')->first();
                                        if (!empty($n->message_id)) {
                                            $tg_data['message_id'] = $n->message_id;
                                            $message = Telegram::editMessageText($tg_data);
                                        } else {
                                            unset($tg_data['message_id']);
                                            $message = Telegram::sendMessage($tg_data);
                                        }
                                    } catch (\Exception $e) {
                                        unset($tg_data['message_id']);
                                        $message = Telegram::sendMessage($tg_data);
                                    }
                                }
                            }
                            //update message_id for all notification releases
                            foreach ($notifications as $notification) {
                                $notification->message_id = $message['message_id'] ?? $notification->message_id;
                                $notification->save();
                            }
                        }
                    }
                }
            } elseif ($user) {
                Log::channel('telegram_bot')->info('USER_CUSTOM_MESSAGE', ['user_id' => $user->id, 'email' => $user->email, 'text' => $update->getMessage()->text]);
            }
        } catch (\Exception $exception) {
            Log::channel('telegram_bot')->error('BOT_EXCEPTION', [$exception]);
        }
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
