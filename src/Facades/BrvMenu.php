<?php

namespace Phobrv\BrvMenu\Facades;

use Illuminate\Support\Facades\Facade;

class BrvMenu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'brvmenu';
    }
}
