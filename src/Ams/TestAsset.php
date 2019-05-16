<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

use Phpsa\Datastore\Ams\Article\ItemAsset;
use Phpsa\Datastore\Ams\ContentAsset;
class TestAsset extends Asset {


	public $name = 'Test Content';
	public $shortname = 'Tests';

	public $children = ContentAsset::class;

	public $accept = ItemAsset::class;
	public $accept_limit = "-1";

	public $max_instances = 5;

	// map our value as equal to whatever the title property is set on save.
	public $value_equals = 'title';
	public $properties = array(
		'title' => array(
			'name' => 'String',
			'type' => self::STRING
		),
		'intro' => array(
			'name' => 'Text',
			'type' => self::TEXT
		),
		'content' => array(
			'name' => 'Html',
			'type' => self::HTML
		),
		'status' => array(
            'name' => 'Dropdown',
            'type' => self::DROPDOWN,
            'options' => ['published' => 'Published', 'unpublished' => 'Unpublished']
		),

		'switch' => array(
			'name' => 'Boolean',
			'type' => self::BOOL
		),
		'file' => array(
			'name' => 'File',
			'type' => self::FILE
		),
		'image' => array(
			'name' => 'image',
			'type' => self::IMG
		),
	);

	public static function about() {
		return 'This asset is a collection of content items to build tabs / accordions etc.';
	}

}

?>
