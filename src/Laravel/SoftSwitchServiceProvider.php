<?php

namespace StephaneCoinon\SoftSwitch\Laravel;

use Illuminate\Support\ServiceProvider;
use StephaneCoinon\SoftSwitch\Api;

class SoftSwitchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Api::class, function ($app) {
            return new Api(
                is_string(config('services.softswitch.url')) ? config('services.softswitch.url') : '',
                is_string(config('services.softswitch.username')) ? config('services.softswitch.username') : '',
                is_string(config('services.softswitch.key')) ? config('services.softswitch.key') : ''
            );
        });

        $this->app->alias(Api::class, 'softswitch');
    }
}