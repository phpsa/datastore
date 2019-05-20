<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class TabAsset extends Asset {

	public $name = 'Tab Content';
	public $shortname = 'Tab';
	public $is_child = true;

	public $value_equals = 'title';

	public $properties = array(
		'title' => array(
			'name' => 'Tab Title',
			'type' => self::STRING,
			'help' => 'Title of your article',
			'required' => true,
			'validation_messages' => 'A Title Is required'
		),
		'content' => array(
			'name' => 'Tab Content',
			'type' => self::HTML
		),
	);

	public static function about() {
		return 'A basic tab asset, allows for a tab title & content';
	}

}
