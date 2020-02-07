const mix = require('laravel-mix');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
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

mix.js('resources/js/app.js', 'public/js')
    .copy('node_modules/jquery.easing/jquery.easing.min.js', 'public/js')
    .copy('resources/js/imageupload.js', 'public/js')
    .copy('resources/js/canvas-particle.js', 'public/js')
    .copy('resources/images', 'public/images')
    .copyDirectory('node_modules/bootstrap-datepicker/dist', 'public/vendor/bootstrap-datepicker')
    .sass('resources/sass/app.scss', 'public/css')
    .copyDirectory('node_modules/fslightbox/index.js', 'public/vendor/fslightbox/index.js')
    // .purgeCss()
    .webpackConfig({
        module: {
            rules: [{
                test: /\.scss/,
                enforce: "pre",
                loader: 'import-glob-loader',
            }]
        },
        plugins: [
            new UglifyJsPlugin()
        ]
    })
    .version();