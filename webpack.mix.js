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

mix.styles([
    'resources/template/css/font-awesome.min.css',
    'resources/template/css/typicons.css',
    'resources/template/css/style1.css',
    'resources/template/css/estilos2.scss',
    'resources/template/css/style.css',
    'resources/template/css/estilo.css',
   //  'resources/template/css/_horizontal-menu.scss',
    'resources/template/css/main.css',
    'resources/template/css/vuetify.css'
 ], 'public/css/template.css')
 .scripts([
    'resources/template/js/jquery.min.js',
    'resources/template/js/jquery.flot.js',
    'resources/template/js/Chart.min.js',
    'resources/template/js/curvedLines.js',
    'resources/template/js/vendor.bundle.base.js',
    'resources/template/js/jsgrid.min.js',
    'resources/template/js/progressbar.min.js',
    'resources/template/js/dashboard.js',
    'resources/template/js/db.js',
    'resources/template/js/hoverable-collapse.js',
    'resources/template/js/js-grid.js',
    'resources/template/js/off-canvas.js',
    'resources/template/js/settings.js',
    'resources/template/js/template.js',
    'resources/template/js/todolist.js',
    'resources/template/js/sweetalert2.all.min.js',
    'resources/js/encabezado.js'
 ], 'public/js/template.js')
 .js(['resources/js/app.js'],'public/js/app.js');
 mix.disableNotifications();
