<?php

namespace mBear\Drive\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Drive
 *
 * @author  Nam Hoang Luu <nam@mbearvn.com>
 * @package mBear\Drive\Facades
 *
 */
class Drive extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mdrive';
    }

}