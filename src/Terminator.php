<?php

namespace ImanGhafoori\Terminator;

class Terminator
{
    /**
     * @param $response
     */
    public function respondWith($response)
    {
        throw new \ImanGhafoori\Terminator\TerminateException($response);
    }
}
