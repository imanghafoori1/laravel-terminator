<?php

namespace Imanghafoori\Responder;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Http\Events\RequestHandled;

class Responder
{
    /**
     * Here we just mimic what laravel core does behind the curtain
     * after we return a response from a controller...
     * and finally "exit"
     * @param $response
     */
    public function sendAndTerminate($response)
    {
        $request = app('request');
        // Send the response to the users browser
        app('router')->prepareResponse($request, $response)->send();
        // Dispatches the core laravel event
        app('events')->dispatch(new RequestHandled($request, $response));
        // This is just needed when we have terminable middlewares in our app.
        app(Kernel::class)->terminate($request, $response);
        exit;
    }
}