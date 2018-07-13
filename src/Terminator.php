<?php

namespace ImanGhafoori\Terminator;

use Illuminate\Http\Exceptions\HttpResponseException;

class Terminator
{
    /**
     * @param $response
     */
    public function respondWith($response)
    {
        throw new HttpResponseException($response);
    }
}
