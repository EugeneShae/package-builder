<?php

namespace Shae\PackageBuilder\Facades;

use Illuminate\Support\Facades\Facade;

class PackageBuilderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'packageBuilder';
    }
}