const mix = require('laravel-mix');

require('laravel-mix-copy-watched');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    output: {
        // Chunks in webpack
        publicPath: '/',
        chunkFilename: 'js/components/[name].js',
    },
});


mix.js('resources/js/app.js', 'public/js');
mix.sass('resources/sass/app.scss', 'public/css');

mix.sass('resources/sass/crud-app.scss', 'public/css');
mix.sass('resources/sass/main.scss', 'public/css');


mix.copyDirectoryWatched('resources/img/**/*', 'public/img', { base: 'resources/img' });
mix.copyDirectoryWatched('resources/css/**/*', 'public/css', { base: 'resources/css' });

if (mix.inProduction()) {
    mix.version();
}
