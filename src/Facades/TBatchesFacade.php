<?php

namespace Ajtarragona\TBatches\Facades; 

use Illuminate\Support\Facades\Facade;

class TBatchesFacade extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'tgn-batches';
    }
}
