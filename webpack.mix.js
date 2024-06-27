let mix = require('laravel-mix');

mix.js('src/resources/js/bootstrap.js', 'public/js')
    .js('src/resources/js/tbatches.js', 'public/js')
    .js('src/resources/js/tbatches-backend.js', 'public/js')
    .sass('src/resources/sass/tbatches-backend.scss', 'public/css')
    .sass('src/resources/sass/tbatches.scss', 'public/css');
	// .copyDirectory('src/resources/images', 'public/img/tbatches');

    
if (mix.inProduction()) {
    mix.version();
}