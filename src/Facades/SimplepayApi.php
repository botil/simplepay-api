<?php

namespace Simplepay\SimplepayApi\Facades;

use Illuminate\Support\Facades\Facade;

class SimplepayApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'simplepay-api';
    }
}
