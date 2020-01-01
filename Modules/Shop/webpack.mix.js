const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.webpackConfig({
    resolve: {
        alias: {
            '@app': path.resolve(__dirname, '../../resources/js/')
        }
    }
});

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/js/app.js', 'js/shop.js')
    .sass( __dirname + '/Resources/sass/app.scss', 'css/shop.css')
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}