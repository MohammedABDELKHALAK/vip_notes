<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $unreadNotifications = auth()->user()->unreadNotifications;
                $notifications = auth()->user()->notifications;
    
                $view->with(compact('unreadNotifications', 'notifications'));
            }
        });
    }
}
