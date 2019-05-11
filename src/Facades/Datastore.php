<?php

namespace Phpsa\Datastore\Facades;

use Illuminate\Support\Facades\Facade;

class Datastore extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'datastore';
    }
}
