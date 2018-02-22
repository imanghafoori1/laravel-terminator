<?php

namespace Imanghafoori\Responder;

use Illuminate\Support\Facades\Facade;

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