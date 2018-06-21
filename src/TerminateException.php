<?php

namespace ImanGhafoori\Terminator;

use Exception;

class TerminateException extends Exception
{
    private $response;

    /**
     * TerminatorException constructor.
     *
     * @param $response
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
       return $this->response;
    }
}