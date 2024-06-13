let mix = require('laravel-mix');

mix.js('src/resources/js/tjobs.js', 'public/js')
    .sass('src/resources/sass/tjobs.scss', 'public/css');
	// .copyDirectory('src/resources/images', 'public/img/tjobs');

    
if (mix.inProduction()) {
    mix.version();
}