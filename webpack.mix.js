const mix = require('laravel-mix');

const postCssPipeline = [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]

mix
    .js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', postCssPipeline)
    .postCss('resources/css/checkout.css', 'public/css', postCssPipeline);

if (mix.inProduction()) {
    mix.disableNotifications().version();
}
