<?php

namespace App\Providers;

use App\Event;
use App\Group;
use App\Observers\EventObserver;
use App\Observers\GroupObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::observe(EventObserver::class);
        Group::observe(GroupObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
