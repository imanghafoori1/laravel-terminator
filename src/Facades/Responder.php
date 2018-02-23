<?php

namespace Imanghafoori\Responder\Facades;

use Illuminate\Support\Facades\Facade;

class Responder extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Imanghafoori\Responder\Responder';
    }
}