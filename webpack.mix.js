const mix = require('laravel-mix');

mix.js(['resources/js/bootstrap.js', 'resources/js/app.js' ], 'public/js/app.js')
    .css('resources/css/app.css', 'public/css/app.css');

mix.styles('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/theme.css');
mix.styles('', 'public/css/app.css');



