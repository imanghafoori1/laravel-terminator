<?php
namespace Imanghafoori\Responder;

use Illuminate\Support\ServiceProvider;

class LaravelResponderServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Imanghafoori\Responder\Responder');
    }
}