<?php

namespace Fc9\Api\Contract\Transformer;

use Fc9\Api\Http\Request;
use Fc9\Api\Transformer\Binding;

interface Adapter
{
    /**
     * Transform a response with a transformer.
     *
     * @param mixed                          $response
     * @param object                         $transformer
     * @param \Fc9\Api\Transformer\Binding $binding
     * @param \Fc9\Api\Http\Request        $request
     *
     * @return array
     */
    public function transform($response, $transformer, Binding $binding, Request $request);
}
