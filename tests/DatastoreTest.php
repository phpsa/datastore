<?php

namespace Phpsa\Datastore\Tests;

use Phpsa\Datastore\Facades\Datastore;
use Phpsa\Datastore\ServiceProvider;
use Orchestra\Testbench\TestCase;

use Phpsa\Datastore\Ams\ContentAsset;

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

	public function testCalledClass(){
		$asset = ContentAsset::getFilename();
		$this->assertEquals('phpsa-datastore::render.content-asset', $asset);

	}
}
