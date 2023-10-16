<?php

namespace MeilleursBiens\LaravelSlackEvents;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;


/**
 * Class SlackApiServiceProvider
 * @package MeilleursBiens\LaravelSlackEventsApi
 */
class SlackEventsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $source = dirname(__DIR__) . '/config/slack_events.php';

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('slack_events.php')], 'slack-events');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('slackEvents');
        }
        $this->mergeConfigFrom($source, 'slackEvents');

        require __DIR__.'/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
