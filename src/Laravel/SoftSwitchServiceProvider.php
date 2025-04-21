<?php

namespace StephaneCoinon\SoftSwitch\Laravel;

use Illuminate\Support\ServiceProvider;
use StephaneCoinon\SoftSwitch\Api;

class SoftSwitchServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/soft-switch.php' => config_path('soft-switch.php'),
        ]);
    }

    public function register(): void
    {
        $this->app->singleton(Api::class, function ($app) {
            return new Api(
                is_string(config('soft-switch.url')) ? config('soft-switch.url') : '',
                is_string(config('soft-switch.username')) ? config('soft-switch.username') : '',
                is_string(config('soft-switch.key')) ? config('soft-switch.key') : ''
            );
        });

        $this->app->alias(Api::class, 'softswitch');
    }
}