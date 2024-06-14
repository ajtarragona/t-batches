<?php

namespace Ajtarragona\TJobs;

use Ajtarragona\TJobs\Commands\PrepareJs;
use Ajtarragona\TJobs\Commands\RunTJob;
use Illuminate\Support\ServiceProvider;
use Ajtarragona\TJobs\Traits\PublishesMigrations;
use Illuminate\Support\Facades\Blade;


class TJobsServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'tgn-jobs');
        
        //cargo rutas
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        //idiomas
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'tgn-jobs');

        $this->publishes([
            __DIR__.'/resources/lang' => resource_path('lang/vendor/tgn-jobs'),
        ], 'tgn-jobs-translations');


        //publico configuracion
        $config = __DIR__.'/Config/tjobs.php';
        
        $this->publishes([
            $config => config_path('tjobs.php'),
        ], 'tgn-jobs-config');


        $this->mergeConfigFrom($config, 'tjobs');


         //publico assets
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/ajtarragona'),
        ], 'tgn-jobs-assets');

        $this->registerCommands();


        $this->registerMigrations(__DIR__.'/database/migrations', 'tgn-jobs-migrations');
       
         //registra directiva sortablecomponent
         Blade::directive('tJobProgress',  function ($expression) {
            return "<?php echo tJobProgress({$expression}); ?>";
        });
    }

    public function registerCommands()
    {
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                RunTJob::class,
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
        $this->app['router']->aliasMiddleware('tjobs-backend', \Ajtarragona\TJobs\Middlewares\TJobsBackend::class);

        //defino facades
        $this->app->bind('tgn-jobs', function(){
            return new \Ajtarragona\TJobs\Services\TJobsService;
        });
        //helpers
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename){
            require_once($filename);
        }
    }
}
