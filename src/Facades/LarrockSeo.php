<?php

namespace Larrock\ComponentAdminSeo\Facades;

use Illuminate\Support\Facades\Facade;

class LarrockSeo extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'larrockseo';
    }

}