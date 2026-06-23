<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CustomDirectiveServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Blade::directive('customDirective', function ($expression) {
            return <<<'PHP'
<?php
$__avatarUser = auth()->user()?->profiles?->full_name ?? 'User';
$__avatarUrl  = 'app/public/avatar-' . $__avatarUser . '.png';
$__avatarPath = storage_path($__avatarUrl);
if (!file_exists($__avatarPath)) {
    \Laravolt\Avatar\Facade::create($__avatarUser)->save($__avatarPath);
}
$__avatarSrc = asset('storage/avatar-' . $__avatarUser . '.png');
echo '<img class="img-90 rounded-circle" src="' . $__avatarSrc . '" style="width:80% !important">';
unset($__avatarUser, $__avatarUrl, $__avatarPath, $__avatarSrc);
?>
PHP;
        });
    }
}
