<?php

namespace Ajtarragona\TBatches;

use Ajtarragona\TBatches\Commands\PrepareJs;
use Ajtarragona\TBatches\Commands\RunBatch;
use Illuminate\Support\ServiceProvider;
use Ajtarragona\TBatches\Traits\PublishesMigrations;
use Illuminate\Support\Facades\Blade;


class TBatchesServiceProvider extends ServiceProvider
{

    use PublishesMigrations;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        //vistas
        $this->loadViewsFrom(__DIR__.'/resources/views', 'tgn-batches');
        
        //cargo rutas
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        //idiomas
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'tgn-batches');

        $this->publishes([
            __DIR__.'/resources/lang' => resource_path('lang/vendor/tgn-batches'),
        ], 'tgn-batches-translations');


        //publico configuracion
        $config = __DIR__.'/Config/tbatches.php';
        
        $this->publishes([
            $config => config_path('tbatches.php'),
        ], 'tgn-batches-config');


        $this->mergeConfigFrom($config, 'tbatches');


         //publico assets
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/ajtarragona'),
        ], 'tgn-batches-assets');

        $this->registerCommands();


        $this->registerMigrations(__DIR__.'/database/migrations', 'tgn-batches-migrations');
       
         //registra directiva sortablecomponent
         Blade::directive('tBatchProgress',  function ($expression) {
            return "<?php echo tBatchProgress({$expression}); ?>";
        });
    }

    public function registerCommands()
    {
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                RunBatch::class,
                PrepareJs::class
                
            ]);
        }
    }
   
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        //registro middleware
        $this->app['router']->aliasMiddleware('tbatches-backend', \Ajtarragona\TBatches\Middlewares\TBatchesBackend::class);

        //defino facades
        $this->app->bind('tgn-batches', function(){
            return new \Ajtarragona\TBatches\Services\TBatchesService;
        });
        //helpers
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename){
            require_once($filename);
        }
    }
}
