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

mix.options({ processCssUrls: false })
	.js('resources/assets/js/app.js', 'public/js')
	.js('resources/assets/js/bank.js', 'public/js')
	.js('resources/assets/js/people.js', 'public/js')
		.js('resources/assets/js/library.js', 'public/js')
	.js('resources/assets/js/imageupload.js', 'public/js')
	.sass('resources/assets/sass/app.scss', 'public/css')
	.styles([
		'node_modules/tags-input/tags-input.css',
	], 'public/css/styles.css')
	.copy('node_modules/font-awesome/fonts', 'public/fonts')
	.copy('node_modules/cropperjs/dist/cropper.min.css', 'public/css')
	.sourceMaps();
	