<?php

namespace Longman\TelegramBot;

use Illuminate\Support\ServiceProvider;

class TelegramServiceProvider extends ServiceProvider
{

    /**
     * @var Telegram[]
     */
    protected $bots = array();

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Return the Telegram Bot Object
     * @param string|null $bot_name
     * @return Telegram
     */
    public function bot($bot_name = null) {
        // if bot name is null grab the first one

        if($bot_name == null) {
            return $this->bots[array_key_first($this->bots)];
        }

        return $this->bots[$bot_name];
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws Exception\TelegramException
     */
    public function boot()
    {
        // Load all the telegram bot configurations
        foreach(config(telegram.bots) as $bot_name => $bot_config) {
            $this->bots[$bot_name] = new Telegram($bot_config['token'], $bot_config['username']);
        }

    }
}
