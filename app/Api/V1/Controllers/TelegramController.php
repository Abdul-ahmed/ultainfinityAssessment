<?php

namespace App\Api\V1\Controllers;

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
        Webhook::create([
            'response' => json_encode($this->request->all()),
        ]);
    }


}
