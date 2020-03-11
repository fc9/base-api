<?php

namespace Fc9\Api\Exception;

use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnknownVersionException extends HttpException
{
    /**
     * Create a new unknown version exception instance.
     *
     * @param string     $message
     * @param \Throwable $previous
     * @param int        $code
     *
     * @return void
     */
    public function __construct($message = null, Throwable $previous = null, $code = 0)
    {
        parent::__construct(400, $message ?: 'The version given was unknown or has no registered routes.', $previous, [], $code);
    }
}
