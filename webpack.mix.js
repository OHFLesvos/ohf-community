const mix = require("laravel-mix");
let path = require("path");

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
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js/"),
            ziggy: path.resolve("vendor/tightenco/ziggy/dist")
        }
    }
});

mix.disableSuccessNotifications();

mix.options({
    processCssUrls: false,
    terser: {
        extractComments: false
    }
})
    .js("resources/js/app.js", "public/js")
    .js("resources/js/accounting.js", "public/js")
    .js("resources/js/fundraising.js", "public/js")
    .js("resources/js/visitors.js", "public/js")
    .js("resources/js/reports.js", "public/js")
    .js("resources/js/people.js", "public/js")
    .js("resources/js/bank.js", "public/js")
    .js("resources/js/cmtyvol.js", "public/js")
    .js("resources/js/shop.js", "public/js")
    .js("resources/js/user_management.js", "public/js")
    .js("resources/js/editor.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .copy(
        "node_modules/@fortawesome/fontawesome-free/webfonts",
        "public/webfonts"
    )
    .copy("node_modules/summernote/dist/font", "public/css/font")
    .vue()
    .extract()
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}
