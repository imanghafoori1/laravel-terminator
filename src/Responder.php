<?php

namespace ImanGhafoori\Terminator;

use Illuminate\Contracts\Routing\ResponseFactory;

class Responder implements ResponseFactory
{
    /**
     * Return a new response from the application.
     *
     * @param  string  $content
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public function make($content = '', $status = 200, array $headers = [])
    {
        respondWith(response()->make($content, $status, $headers));
    }

    /**
     * Create a new "no content" response.
     *
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public function noContent($status = 204, array $headers = [])
    {
        respondWith(response()->noContent($status, $headers));
    }

    /**
     * Return a new view response from the application.
     *
     * @param  string  $view
     * @param  array  $data
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public function view($view, $data = [], $status = 200, array $headers = [])
    {
        respondWith(response()->view($view, $data, $status, $headers));
    }

    /**
     * Return a new JSON response from the application.
     *
     * @param  string|array  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        respondWith(response()->json($data, $status, $headers, $options));
    }

    /**
     * Return a new JSONP response from the application.
     *
     * @param  string  $callback
     * @param  string|array  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonp($callback, $data = [], $status = 200, array $headers = [], $options = 0)
    {
        respondWith(response()->jsonp($callback, $data, $status, $headers, $options));
    }

    /**
     * Return a new streamed response from the application.
     *
     * @param  \Closure  $callback
     * @param  int  $status
     * @param  array  $headers
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function stream($callback, $status = 200, array $headers = [])
    {
        respondWith(response()->stream($callback, $status, $headers));
    }

    /**
     * Return a new streamed response as a file download from the application.
     *
     * @param  \Closure  $callback
     * @param  string|null  $name
     * @param  array  $headers
     * @param  string|null  $disposition
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function streamDownload($callback, $name = null, array $headers = [], $disposition = 'attachment')
    {
        respondWith(response()->streamDownload($callback, $name, $headers, $disposition));
    }

    /**
     * Create a new file download response.
     *
     * @param  \SplFileInfo|string  $file
     * @param  string|null  $name
     * @param  array  $headers
     * @param  string|null  $disposition
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($file, $name = null, array $headers = [], $disposition = 'attachment')
    {
        respondWith(response()->download($file, $name, $headers, $disposition));
    }

    /**
     * Return the raw contents of a binary file.
     *
     * @param  \SplFileInfo|string  $file
     * @param  array  $headers
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function file($file, array $headers = [])
    {
        respondWith(response()->file($file, $headers));
    }

    /**
     * Create a new redirect response to the given path.
     *
     * @param  string  $path
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectTo($path, $status = 302, $headers = [], $secure = null)
    {
        return new Chain([$path, $status, $headers, $secure], 'redirectTo');
    }

    /**
     * Create a new redirect response to a named route.
     *
     * @param  string  $route
     * @param  array  $parameters
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToRoute($route, $parameters = [], $status = 302, $headers = [])
    {
        return new Chain([$route, $parameters, $status, $headers], 'redirectToRoute');
    }

    /**
     * Create a new redirect response to a controller action.
     *
     * @param  string  $action
     * @param  array  $parameters
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToAction($action, $parameters = [], $status = 302, $headers = [])
    {
        return new Chain([$action, $parameters, $status, $headers], 'redirectToAction');
    }

    /**
     * Create a new redirect response, while putting the current URL in the session.
     *
     * @param  string  $path
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectGuest($path, $status = 302, $headers = [], $secure = null)
    {
        return new Chain([$path, $status, $headers, $secure], 'redirectGuest');
    }

    /**
     * Create a new redirect response to the previously intended location.
     *
     * @param  string  $default
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToIntended($default = '/', $status = 302, $headers = [], $secure = null)
    {
        return new Chain([$default, $status, $headers, $secure], 'redirectToIntended');
    }
}
