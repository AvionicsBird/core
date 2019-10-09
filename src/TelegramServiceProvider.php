<?php

namespace Longman\TelegramBot;

use Illuminate\Contracts\Container\Container as Application;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Telegram\Bot\Api;

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
        $this->registerManager($this->app);
        $this->registerBindings($this->app);
    }

    /**
     * Register the manager class.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    protected function registerManager(Application $app)
    {
        $app->singleton('telegram', function ($app) {
            $config = (array)$app['config']['telegram'];

            return (new BotsManager($config))->setContainer($app);
        });

        $app->alias('telegram', BotsManager::class);
    }

    /**
     * Register the bindings.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    protected function registerBindings(Application $app)
    {
        $app->bind('telegram.bot', function ($app) {
            $manager = $app['telegram'];

            return $manager->bot();
        });

        $app->alias('telegram.bot', Api::class);
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

    /**
     * Setup the config.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    protected function setupConfig(Application $app)
    {
        $source = __DIR__.'/config/telegram.php';

        if ($app instanceof LaravelApplication && $app->runningInConsole()) {
            $this->publishes([$source => config_path('telegram.php')], 'config');
        } elseif ($app instanceof LumenApplication) {
            $app->configure('telegram');
        }

        $this->mergeConfigFrom($source, 'telegram');
    }
}
