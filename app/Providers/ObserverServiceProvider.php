<?php

namespace App\Providers;

use App\{User, Activity, Post};
use App\Observers\{UserObserver, ActivityObserver, PostObserver};
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
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
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Post::observe(PostObserver::class);
        Activity::observe(ActivityObserver::class);
    }
}
