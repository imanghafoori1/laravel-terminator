<?php

namespace ImanGhafoori\Terminator;

use Illuminate\Contracts\Routing\ResponseFactory;

class Responder implements ResponseFactory
{
    public function json(...$args)
    {
        respondWith(response()->json(...$args));
    }

    public function make(...$args)
    {
        respondWith(response()->make(...$args));
    }

    public function view(...$args)
    {
        respondWith(response()->view(...$args));
    }

    public function jsonp(...$args)
    {
        respondWith(response()->jsonp(...$args));
    }

    public function stream(...$args)
    {
        respondWith(response()->stream(...$args));
    }

    public function download(...$args)
    {
        respondWith(response()->download(...$args));
    }

    public function redirectTo(...$args)
    {
        respondWith(response()->redirectTo(...$args));
    }

    public function redirectToRoute(...$args)
    {
        respondWith(response()->redirectToRoute(...$args));
    }

    public function redirectToAction(...$args)
    {
        respondWith(response()->redirectToAction(...$args));
    }

    public function redirectGuest(...$args)
    {
        respondWith(response()->redirectGuest(...$args));
    }

    public function redirectToIntended(...$args)
    {
        respondWith(response()->redirectToIntended(...$args));
    }
}