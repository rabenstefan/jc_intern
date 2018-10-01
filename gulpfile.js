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
    mix.sass('app.scss');

    mix.scripts([
        'main.js',
        'notify.min.js',
        'scrollspy.js',
        'affix.js',
        'dropdown.js',
        'transition.js',
        'collapse.js',
        'js.cookie.js',
        'dateFilters.js'
    ]);

    // Enable cache busting. Make sure to also use Html::styleV and Html::scriptV instead of their non-versioned counterparts
    mix.version([
        'css/app.css',
        'js/all.js',
        'js/jquery.min.js',
        'js/jquery.modal.min.js',
        'js/jquery-ui.custom.min.js',
        'js/moment.min.js',
        'js/fullcalendar.min.js',
        'js/lang/de.js',
        'js/lang/en.js'
    ]);
});
