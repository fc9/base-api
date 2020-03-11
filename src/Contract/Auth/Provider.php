<?php

namespace Fc9\Api\Contract\Auth;

use Fc9\Api\Routing\Route;
use Illuminate\Http\Request;

interface Provider
{
    /**
     * Authenticate the request and return the authenticated user instance.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Fc9\Api\Routing\Route $route
     *
     * @return mixed
     */
    public function authenticate(Request $request, Route $route);
}
