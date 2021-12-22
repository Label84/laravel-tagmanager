<?php

namespace Label84\TagManager\Facades;

use Illuminate\Support\Facades\Facade;

class TagManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'tagmanager';
    }
}
