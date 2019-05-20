<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class TabsAsset extends Asset {


	public $name = 'Tab Page';
	public $shortname = 'Tabs';

	public $children = TabAsset::class;


	// map our value as equal to whatever the title property is set on save.
	public $value_equals = 'title';
	public $status_equals = 'status';

	public $properties = array(
		'title' => array(
			'name' => 'Title',
			'type' => self::STRING
		),
		'content' => array(
			'name' => 'Introduction',
			'type' => self::HTML
		),
		'status' => array(
            'name' => 'Published',
            'type' => self::DROPDOWN,
			'options' => ['published' => 'Published', 'unpublished' => 'Unpublished'],
			'published' => ['published']
        ),
	);

	public static function about() {
		return 'This asset is a collection of content items to build tabs / accordions etc.';
	}

}
