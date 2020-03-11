<?php

namespace Fc9\Api\Event;

use Fc9\Api\Http\Request;
use Illuminate\Contracts\Container\Container;

class RequestWasMatched
{
    /**
     * Request instance.
     *
     * @var \Fc9\Api\Http\Request
     */
    public $request;

    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    public $app;

    /**
     * Create a new request was matched event.
     *
     * @param \Fc9\Api\Http\Request                   $request
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    public function __construct(Request $request, Container $app)
    {
        $this->request = $request;
        $this->app = $app;
    }
}
