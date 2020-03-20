<?php

namespace Fc9\Api\Facade;

use \Illuminate\Support\Facades\Http as Facades;

class Http extends Facades
{
    protected static $status_code = [
        /* Informative */
        'continue' => ['code' => 100, 'message' => 'Continue'],
        'changing_protocol' => ['code' => 101, 'message' => 'Changing the protocol'],
        /* Success */
        'ok' => ['code' => 200, 'message' => 'Ok'],
        'create' => ['code' => 201, 'message' => 'Create'],
        'accept' => ['code' => 202, 'message' => 'Accept'],
        'no-authorized' => ['code' => 203, 'message' => 'Not authorized'],
        'no-content' => ['code' => 204, 'message' => 'No content'],
        'reset' => ['code' => 205, 'message' => 'Reset'],
        'partial_content' => ['code' => 206, 'message' => 'Partial content'],
        'status_multi' => ['code' => 207, 'message' => 'Status Multi'],
        /* Redirection */
        'multiple-choice' => ['code' => 300, 'message' => 'Multiple choice'],
        'permanently-moved' => ['code' => 301, 'message' => 'Permanently moved'],
        'found' => ['code' => 302, 'message' => 'Found'],
        'no-modifiqued' => ['code' => 304, 'message' => 'No modifiqued'],
        'use-proxy' => ['code' => 305, 'message' => 'Use Proxy'],
        'temporary-redirect' => ['code' => 307, 'message' => 'Temporary redirect'],
        /* Client Error */
        'invalid-request' => ['code' => 400, 'message' => 'Invalid request'],
        'unauthorized-request' => ['code' => 401, 'message' => 'Not authorized'],
        'payment-required' => ['code' => 402, 'message' => 'Payment required'],
        'forbidden' => ['code' => 403, 'message' => 'Forbidden'],
        'not_found' => ['code' => 404, 'message' => 'Not found'],
        'method-not-found' => ['code' => 405, 'message' => 'Method not allowed'],
        'not-acceptable' => ['code' => 406, 'message' => 'not acceptable'],
        'proxy-auth' => ['code' => 407, 'message' => 'Proxy authntication required'],
        'request-elapsed' => ['code' => 408, 'message' => 'Request time has elapsed'],
        'conflict' => ['code' => 409, 'message' => 'Conflict'],
        /* Server Error */
        'server-error' => ['code' => 500, 'message' => 'Internal server error'],
        'not-implemented' => ['code' => 501, 'message' => 'Not implemented'],
        'bad-gateway' => ['code' => 502, 'message' => 'Bad Gateway'],
        'service-unavailable' => ['code' => 503, 'message' => 'Service unavailable'],
        'time-out-gateway' => ['code' => 504, 'message' => 'Time-Out Gateway'],
        'http-not-supported' => ['code' => 505, 'message' => 'Conflict'],
    ];

    public static function status($id)
    {
        return improve(self::$status_code[$id]);
    }

}