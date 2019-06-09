<?php

namespace Imanghafoori\Terminator\Test;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
//            \Imanghafoori\MakeSure\MakeSureServiceProvider::class,
        ];
    }
}
