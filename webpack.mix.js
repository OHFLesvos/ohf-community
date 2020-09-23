const mix = require('laravel-mix');

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
    node: {
      fs: "empty"
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js/'),
            ziggy: path.resolve('vendor/tightenco/ziggy/dist/js/route.js'),
        }
    }
});

mix.disableSuccessNotifications();

mix.options({
        processCssUrls: false,
        terser: {
            extractComments: false,
        }
    })
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/fundraising.js', 'public/js')
    .js('resources/js/visitors.js', 'public/js')
    .js('resources/js/people.js', 'public/js')
    .js('resources/js/bank.js', 'public/js')
    .js('resources/js/library.js', 'public/js')
    .js('resources/js/cmtyvol.js', 'public/js')
    .js('resources/js/shop.js', 'public/js')
    .js('resources/js/user_management.js', 'public/js')
    .js('resources/js/editor.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
    .copy('node_modules/summernote/dist/summernote-bs4.js', 'public/js')
    .copy('node_modules/summernote/dist/summernote-bs4.css', 'public/css')
    .copy('node_modules/summernote/dist/font', 'public/css/font')
    .sourceMaps();
