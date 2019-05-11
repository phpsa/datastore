<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class ContentAsset extends Asset {

	public $name = 'Basic Content';
	public $shortname = 'Content';
	public $is_child = false;

	public $value_equals = 'title';
    public $status_equals = 'status';

	public $properties = array(
		'title' => array(
			'name' => 'Title',
			'type' => self::STRING,
			'help' => 'Title of your article',
			'required' => true,
			'validation_messages' => 'A Title Is required'
		),
		'content' => array(
			'name' => 'Content',
			'type' => self::HTML
		),
		'status' => array(
            'name' => 'Published',
            'type' => self::DROPDOWN,
            'options' => ['published' => 'Published', 'unpublished' => 'Unpublished']
        ),
	);

	public static function about() {
		return 'A basic content asset, allows for a title and some html content';
	}

}
