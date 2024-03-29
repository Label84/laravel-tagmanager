<?php

namespace Label84\TagManager\Facades;

use Illuminate\Support\Facades\Facade;

class MeasurementProtocol extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'measurement_protocol';
    }
}
