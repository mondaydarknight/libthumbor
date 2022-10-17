<?php

namespace Thumbor\Facades;

use Illuminate\Support\Facades\Facade;

class Thumbor extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'thumbor';
    }
}
