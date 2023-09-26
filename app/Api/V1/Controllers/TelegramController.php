<?php

namespace App\Api\V1\Controllers;

use App\Models\User;
use App\Models\Webhook;
use Telegram\Bot\Api;
use Telegram\Bot\BotsManager;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return view('home');
    }

    public function testing()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        return $telegram->getMe();
    }

    public function usingBotManager()
    {
        $config = [
            'bots' => [
                'ultainfinityAssessmentBot' => [
                    'token' => env('TELEGRAM_BOT_TOKEN'),
                ],
            ]
        ];

        $telegram = new BotsManager($config);
        return $telegram->bot('ultainfinityAssessmentBot')->getMe();
    }

    public function webhook()
    {
        if (array_key_exists('text', $this->request->message)) {
            if ($this->request->message['text'] == '/start') {
                $user = User::where(['telegram_id' => $this->request->message->from->id])->first();
                if (!$user) {
                    User::create([
                        'first_name' => $this->request->message->from->first_name,
                        'last_name' => $this->request->message->from->last_name,
                        'telegram_id' => $this->request->message->from->id,
                    ]);
                }
            }
        } else {
            Webhook::create([
                'response' => json_encode($this->request->all()),
            ]);
        }
    }

    public function login()
    {
        $user = User::where(['telegram_id' => $this->request->telegram_id])->first();
        if (!$user) {
            User::create([
                'first_name' => $this->request->first_name,
                'last_name' => $this->request->last_name,
                'telegram_id' => $this->request->telegram_id,
            ]);
        }
        return response()->json([
            'status' => 'success',
            'code' => Response::HTTP_CREATED,
            'message' => 'User subscribed successfully',
            'data' => null
        ], Response::HTTP_CREATED);
    }


}
