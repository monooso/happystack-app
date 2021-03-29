const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

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
    mix.version();
}
