let mix = require('laravel-mix');

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
	}
});

/* Allow multiple Laravel Mix applications*/
require('laravel-mix-merge-manifest');
mix.mergeManifest();

mix.options({ processCssUrls: false })
	.js('resources/js/app.js', 'public/js')
	.js('resources/js/imageupload.js', 'public/js')
	.js('resources/js/editor.js', 'public/js')
	.sass('resources/sass/app.scss', 'public/css')
	.styles([
	], 'public/css/styles.css')
	.copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
	.copy('node_modules/cropperjs/dist/cropper.min.css', 'public/css')
	.copy('node_modules/summernote/dist/summernote-bs4.js', 'public/js')
	.copy('node_modules/summernote/dist/summernote-bs4.css', 'public/css')
	.copy('node_modules/summernote/dist/font', 'public/css/font')
	.sourceMaps();
	