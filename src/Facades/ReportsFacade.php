<?php

namespace Ajtarragona\TJobs\Facades; 

use Illuminate\Support\Facades\Facade;

class TJobsFacade extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'tgn-jobs';
    }
}
