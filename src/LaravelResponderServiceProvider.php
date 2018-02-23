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
        $this->app->singleton('Imanghafoori\Responder\Responder', function () {
            return new Responder();
        });
    }
}