const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/js/app.js', 'js/collaboration.js')
    .sass( __dirname + '/Resources/sass/app.scss', 'css/collaboration.css')
    .js(__dirname + '/Resources/js/calendar.js', 'js/calendar.js')
    .js(__dirname + '/Resources/js/tasks.js', 'js/tasks.js')
    .copy('node_modules/fullcalendar/dist/fullcalendar.min.css', '../../public/css')
	.copy('node_modules/fullcalendar/dist/fullcalendar.min.js', '../../public/js')
    .copy('node_modules/fullcalendar-scheduler/dist/scheduler.min.css', '../../public/css')
    .copy('node_modules/fullcalendar-scheduler/dist/scheduler.min.js', '../../public/js')
    .copy('node_modules/moment/min/moment-with-locales.min.js', '../../public/js')
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}