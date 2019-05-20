<?php

return [
	'assets' => [
	//	Phpsa\Datastore\Ams\RobotsTxtAsset::class,
		Phpsa\Datastore\Ams\ContentAsset::class,
		Phpsa\Datastore\Ams\Article\CategoryAsset::class,
		Phpsa\Datastore\Ams\Article\ItemAsset::class,
		Phpsa\Datastore\Ams\TabsAsset::class,
	//	Phpsa\Datastore\Ams\TestAsset::class
	],
	'urlprefix' => 'ams'
];
