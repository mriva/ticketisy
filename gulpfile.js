var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss')
        .scripts([
            'resources/assets/vendor/jquery/dist/jquery.min.js',
            'resources/assets/vendor/angular/angular.min.js',
            'resources/assets/vendor/bootstrap/dist/js/bootstrap.min.js',
            'resources/assets/vendor/angular-bootstrap/ui-bootstrap.min.js',
            'resources/assets/vendor/angular-bootstrap/ui-bootstrap-tpls.min.js',
            'resources/assets/vendor/angular-ui-router/release/angular-ui-router.min.js',
            'resources/assets/vendor/moment/min/moment-with-locales.min.js',
            'resources/assets/vendor/underscore/underscore-min.js'
        ], 'public/js/libs.js', './')
        .scripts([
            'resources/assets/js/app.js'
        ], 'public/js/app.js', './');
});
