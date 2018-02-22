<?php
if (! function_exists('sendAndTerminate')) {
    /**
     * Get the path to the resources folder.
     *
     * @param  string  $response
     * @return string
     */
    function sendAndTerminate($response = '')
    {
        app('Imanghafoori\Responder\Responder')->sendAndTerminate($response);
    }
}