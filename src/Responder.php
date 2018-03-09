<?php

namespace Imanghafoori\Responder;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Session\CookieSessionHandler;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Http\Events\RequestHandled;

class Responder
{
    private $manager;

    /**
     * Here we just mimic what laravel core does behind the curtain
     * after we return a response from a controller...
     * and finally "exit"
     * @param $response
     */
    public function sendAndTerminate($response)
    {
        $this->manager = $session = app('session');
        $request = app('request');
        if ($this->sessionConfigured()) {
            $this->storeCurrentUrl($request, $session);

            $this->addCookieToResponse($response, $session);
        }
        $response = $this->encrypt($response);
        // Send the response to the users browser
        app('router')->prepareResponse($request, $response)->send();
        // Dispatches the core laravel event
        app('events')->dispatch(new RequestHandled($request, $response));
        // This is just needed when we have terminable middlewares in our app.
        app(Kernel::class)->terminate($request, $response);
        exit;
    }

    /**
     * Determine if a session driver has been configured.
     *
     * @return bool
     */
    protected function sessionConfigured()
    {
        return ! is_null($this->manager->getSessionConfig()['driver'] ?? null);
    }

    /**
     * Store the current URL for the request if necessary.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Session\Session  $session
     * @return void
     */
    protected function storeCurrentUrl(Request $request, $session)
    {
        if ($request->method() === 'GET' && $request->route() && ! $request->ajax()) {
            $session->setPreviousUrl($request->fullUrl());
        }
    }

    /**
     * Add the session cookie to the application response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  \Illuminate\Contracts\Session\Session  $session
     * @return void
     */
    protected function addCookieToResponse(Response $response, Session $session)
    {
        if ($this->usingCookieSessions()) {
            $this->manager->driver()->save();
        }

        if ($this->sessionIsPersistent($config = $this->manager->getSessionConfig())) {
            $response->headers->setCookie(new Cookie(
                $session->getName(), $session->getId(), $this->getCookieExpirationDate(),
                $config['path'], $config['domain'], $config['secure'] ?? false,
                $config['http_only'] ?? true, false, $config['same_site'] ?? null
            ));
        }
    }

    /**
     * Determine if the session is using cookie sessions.
     *
     * @return bool
     */
    protected function usingCookieSessions()
    {
        if ($this->sessionConfigured()) {
            return $this->manager->driver()->getHandler() instanceof CookieSessionHandler;
        }

        return false;
    }

    /**
     * Determine if the configured session driver is persistent.
     *
     * @param  array|null  $config
     * @return bool
     */
    protected function sessionIsPersistent(array $config = null)
    {
        $config = $config ?: $this->manager->getSessionConfig();

        return ! in_array($config['driver'], [null, 'array']);
    }
    /**
     * Get the cookie lifetime in seconds.
     *
     * @return \DateTimeInterface
     */
    protected function getCookieExpirationDate()
    {
        $config = $this->manager->getSessionConfig();

        return $config['expire_on_close'] ? 0 : Carbon::now()->addMinutes($config['lifetime']);
    }
    /**
     * Encrypt the cookies on an outgoing response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function encrypt(Response $response)
    {
        foreach ($response->headers->getCookies() as $cookie) {
            $response->headers->setCookie($this->duplicate(
                $cookie, $this->encrypter->encrypt($cookie->getValue())
            ));
        }

        return $response;
    }
    /**
     * Duplicate a cookie with a new value.
     *
     * @param  \Symfony\Component\HttpFoundation\Cookie  $c
     * @param  mixed  $value
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    protected function duplicate(Cookie $c, $value)
    {
        return new Cookie(
            $c->getName(), $value, $c->getExpiresTime(), $c->getPath(),
            $c->getDomain(), $c->isSecure(), $c->isHttpOnly(), $c->isRaw(),
            $c->getSameSite()
        );
    }

}
