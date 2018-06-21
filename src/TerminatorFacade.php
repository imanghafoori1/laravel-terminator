<?php

namespace ImanGhafoori\Terminator;

use Illuminate\Support\Facade;

class TerminatorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \ImanGhafoori\Terminator\Terminator::class;
    }
}