<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use App\Conversations\HighscoreConversation;
use App\Conversations\QuizConversation;
use App\Conversations\PrivacyConversation;


class BotManController extends Controller
{

    public function handle()
    {

        DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
        $config = [
            'user_cache_time' => 720,

            'config' => [
                'conversation_cache_time' => 720,
            ],

            "telegram" => [
                "token" => env('TELEGRAM_TOKEN'),
            ]
        ];

        // // Create BotMan instance
        $botman = BotManFactory::create($config, new LaravelCache());

        $botman = app('botman');
        $botman->hears('start', function (BotMan $bot) {
            $bot->reply('Hola! ¿Como puedo ayudarte?');
        });

        $botman->hears('start|/start', function (BotMan $bot) {
            $bot->startConversation(new QuizConversation());
        })->stopsConversation();

        $botman->hears('/highscore|highscore', function (BotMan $bot) {
            $bot->startConversation(new HighscoreConversation());
        })->stopsConversation();

        $botman->hears('/deletedata|deletedata', function (BotMan $bot) {
            $bot->startConversation(new PrivacyConversation());
        })->stopsConversation();


        $botman->listen();
        //dd($botman);
    }



}
