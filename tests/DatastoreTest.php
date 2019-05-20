<?php

namespace Phpsa\Datastore\Tests;

use Phpsa\Datastore\Facades\Datastore;
use Phpsa\Datastore\ServiceProvider;
use Orchestra\Testbench\TestCase;

class DatastoreTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'datastore' => Datastore::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
