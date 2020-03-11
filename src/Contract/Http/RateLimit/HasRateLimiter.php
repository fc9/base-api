<?php

namespace Fc9\Api\Contract\Http\RateLimit;

use Fc9\Api\Http\Request;
use Illuminate\Container\Container;

interface HasRateLimiter
{
    /**
     * Get rate limiter callable.
     *
     * @param \Illuminate\Container\Container $app
     * @param \Fc9\Api\Http\Request         $request
     *
     * @return string
     */
    public function getRateLimiter(Container $app, Request $request);
}
