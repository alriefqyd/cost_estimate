const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/realtime.js', 'public/js')
    .js('resources/js/collab.js', 'public/js')
    .js('resources/js/estimate-discipline/index.jsx', 'public/js/estimate-discipline.js')
    .react()
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ]);

if (mix.inProduction()) {
    mix.version();
}
mix.browserSync('127.0.0.1:8000');
