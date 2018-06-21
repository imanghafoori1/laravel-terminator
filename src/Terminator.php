<?php

namespace ImanGhafoori\Terminator;

class Terminator
{
    /**
     * @param $response
     * @throws \ImanGhafoori\Terminator\TerminateException
     */
    public function respondWith($response)
    {
        throw new \ImanGhafoori\Terminator\TerminateException($response);
    }
}
