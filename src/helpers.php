<?php

if (! function_exists('sendAndTerminate')) {
    /**
     * Get the path to the resources folder.
     *
     * @param  string  $response
     * @return null|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function sendAndTerminate($response = null)
    {
        return respondWith($response);
    }
}

if (! function_exists('respondWith')) {
    /**
     * Get the path to the resources folder.
     *
     * @param  string  $response
     * @return null|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @throws \ImanGhafoori\Terminator\TerminateException
     */
    function respondWith($response = null)
    {
        if (is_null($response)) {
            return app(ImanGhafoori\Terminator\Responder::class);
        }
        app(ImanGhafoori\Terminator\Terminator::class)->respondWith($response);
    }
}
