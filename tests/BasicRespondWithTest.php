<?php

namespace Imanghafoori\Terminator\Test;

use Illuminate\Support\Facades\Route;
use Imanghafoori\MakeSure\Facades\MakeSure;

class BasicRespondWithTest extends \Orchestra\Testbench\TestCase
{
    public function testControllerActionIsAuthorized2()
    {
        Route::get('/welcome', function() {
            respondWith()->redirectTo('/');
        })->name('welcome.name');

        MakeSure::about($this)->sendingGetRequest('/welcome')->isRespondedWith()
            ->redirect('/');
    }

    public function testControllerActionIsAuthorized2q()
    {
        Route::get('/', function() {
        })->name('name');

        Route::get('/welcome', function() {
            respondWith()->redirectToRoute('name');
        });

        Route::get('/welcome2', function() {
            respondWith(redirect()->route('name'));
        });

        MakeSure::about($this)
            ->sendingGetRequest('/welcome')
            ->isRespondedWith()
            ->redirect('/');

        MakeSure::about($this)
            ->sendingGetRequest('/welcome2')
            ->isRespondedWith()
            ->redirect('/');
    }

    public function testControllerActionIsAuthorized2asc()
    {
        Route::get('/', function() {
        })->name('name');

        Route::post('/welcome', function() {
            respondWith()->redirectToRoute('name')->withErrors(['foo', 'bar']);
        });

        $this->post('/welcome')->assertSessionHasErrors()->assertRedirect('/');
    }
}
