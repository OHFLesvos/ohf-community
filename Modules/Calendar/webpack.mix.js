const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'js/calendar.js')
    .sass( __dirname + '/Resources/assets/sass/app.scss', 'css/calendar.css')
    .copy('node_modules/fullcalendar/dist/fullcalendar.min.css', '../../public/css')
	.copy('node_modules/fullcalendar/dist/fullcalendar.min.js', '../../public/js')
    .copy('node_modules/fullcalendar-scheduler/dist/scheduler.min.css', '../../public/css')
    .copy('node_modules/fullcalendar-scheduler/dist/scheduler.min.js', '../../public/js')
    .copy('node_modules/moment/min/moment-with-locales.min.js', '../../public/js')
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}