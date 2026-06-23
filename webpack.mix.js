const mix = require('laravel-mix');
const TerserPlugin = require('terser-webpack-plugin');

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/realtime.js', 'public/js')
    .js('resources/js/collab.js', 'public/js')
    .js('resources/js/estimate-discipline/index.jsx', 'public/js/estimate-discipline.js')
    .react()
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ]);

mix.webpackConfig({
    optimization: {
        minimizer: [
            new TerserPlugin({
                terserOptions: {
                    ecma: 2020,
                },
            }),
        ],
    },
});

if (mix.inProduction()) {
    mix.version();
}
mix.browserSync('127.0.0.1:8000');
