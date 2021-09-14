<?php

namespace SnowPenguinStudios\LaravelModelStatus\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see SnowPenguinStudios\LaravelModelStatus\Models\Status
 */
class StatusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'status';
    }
}
