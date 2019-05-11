<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class TabsAsset extends Asset {


	public $name = 'Tab Content';
	public $shortname = 'Tabs';

	public $children = 'Phpsa\Datastore\Ams\ContentAsset';


	// map our value as equal to whatever the title property is set on save.
	public $value_equals = 'title';
	public $properties = array(
		'title' => array(
			'name' => 'Title',
			'type' => self::STRING
		),
		'content' => array(
			'name' => 'Introduction',
			'type' => self::HTML
		)
	);

	public static function about() {
		return 'This asset is a collection of content items to build tabs / accordions etc.';
	}

}

?>
