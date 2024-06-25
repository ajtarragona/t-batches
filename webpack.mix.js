let mix = require('laravel-mix');

mix.js('src/resources/js/tbatches.js', 'public/js')
    .sass('src/resources/sass/tbatches.scss', 'public/css');
	// .copyDirectory('src/resources/images', 'public/img/tbatches');

    
if (mix.inProduction()) {
    mix.version();
}