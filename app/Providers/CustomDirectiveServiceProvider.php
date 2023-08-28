<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Laravolt\Avatar\Facade as Avatar;

class CustomDirectiveServiceProvider extends ServiceProvider
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
        Blade::directive('customDirective', function($expression){
            $user = auth()->user()->profiles?->full_name;
            $url = 'app/public/avatar-'.$user.'.png';
            $path = storage_path($url);
            if(!file_exists($path)) {
                Avatar::create($user)->save(storage_path($url));
            }

            $urlImage = asset("storage/avatar-$user.png");
            return '<img class="img-90 rounded-circle" src="'.$urlImage.'" style="width:80% !important">';
        });
    }
}
