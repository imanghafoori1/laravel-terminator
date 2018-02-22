<?php

namespace Imanghafoori\Responder\Facades;

use Illuminate\Support\Facades\Facade;
use Imanghafoori\Responder\Responder;

class ResponderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Responder::class;
    }
}