<?php
namespace Imanghafoori\Responder;

class LaravelResponderServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Responder::class, function () {
            return new Responder();
        });
    }
}