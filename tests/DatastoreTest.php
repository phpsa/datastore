<?php

namespace Phpsa\Datastore\Tests;

use Phpsa\Datastore\Datastore;
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
		$asset = ContentAsset::getAssetView();
		$this->assertEquals('phpsa-datastore::render.content-asset', $asset);

	}

	public function testMapping()
    {
		$tester = Datastore::getAsset(ContentAsset::class);

		$tester->prop('title', 'Moe Title');
        $tester->prop('content', '<p>Rhoncus hac aliquam aliquam! Et mauris, et quis platea ut elementum natoque sit natoque lectus augue integer aliquam porta rhoncus nec, cursus diam a parturient augue ut! Tincidunt eros urna lacus lorem, sit scelerisque. Proin duis auctor ut. Turpis? Sed, diam elit sed velit dapibus phasellus, pulvinar mattis! Sociis augue in parturient sed ultricies et.</p>');
		$tester->prop('status', 'published');

		$this->assertEquals('<span>Moe Title</span>', $tester->render('title'));
	}
}
