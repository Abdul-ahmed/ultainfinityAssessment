<?php

namespace App\Api\V1\Controllers;

use App\Models\User;
use App\Models\Webhook;
use Telegram\Bot\Api;
use Telegram\Bot\BotsManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Illuminate\Validation\Validator;

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
                $user = User::where(['telegram_id' => $this->request->message['from']['id']])->first();
                if (!$user) {
                    User::create([
                        'first_name' => $this->request->message['from']['first_name'],
                        'last_name' => $this->request->message['from']['last_name'],
                        'telegram_id' => $this->request->message['from']['id'],
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
        $this->validateLoginRequest();
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

    public function validateLoginRequest()
    {
        return $this->validate($this->request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'telegram_id' => 'required|string',
            'username' => 'required|string',
        ]);
    }

    public function channelLogin()
    {
        return redirect()->to('https://t.me/ultainfinityAssessment');
    }

    public function sendMessage()
    {
        $this->validateMessageRequest();
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        try {
            return $telegram->sendMessage([
                'chat_id' => $this->request->user_telegram_id,
                'text' => $this->request->message
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
        
        
        // $messageId = $response->getMessageId();
    }

    public function validateMessageRequest()
    {
        return $this->validate($this->request, [
            'user_telegram_id' => 'required|string',
            'message' => 'required|string'
        ]);
    }
}
