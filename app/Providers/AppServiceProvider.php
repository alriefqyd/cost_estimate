<?php

namespace App\Providers;

use App\Helper\GenerateTableWorkItemsHelper;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return bool
     */
    public function register()
    {
        $this->app->singleton(
            'generateDB',
            GenerateTableWorkItemsHelper::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
