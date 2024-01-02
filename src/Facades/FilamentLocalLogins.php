<?php

namespace BetterFuturesStudio\FilamentLocalLogins\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BetterFuturesStudio\FilamentLocalLogins\FilamentLocalLogins
 */
class FilamentLocalLogins extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BetterFuturesStudio\FilamentLocalLogins\FilamentLocalLogins::class;
    }
}
