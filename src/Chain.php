<?php

namespace ImanGhafoori\Terminator;

class Chain
{
    protected $data;
    protected $method;

    /**
     * Chain constructor.
     *
     * @param $args
     */
    public function __construct($args, $method)
    {
        $this->data[] = [$args, $method];
    }

    public function __call($method, $args)
    {
        $this->data[] = [$args, $method];
        return $this;
    }

    public function __destruct()
    {
        $resp = response();
        foreach ($this->data as $data) {
            $resp = $resp->{$data[1]}(...$data[0]);
        }
        respondWith($resp);
    }
}