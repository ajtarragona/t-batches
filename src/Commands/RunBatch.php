<?php

namespace Ajtarragona\TBatches\Commands;

use Illuminate\Console\Command;

use \Artisan;
use Illuminate\Support\Facades\File;  

class RunBatch extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ajtarragona:batch:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a specific batch';


    

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
   
      
        
    }



}
