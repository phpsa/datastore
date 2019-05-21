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

	/*
	@TODO's
	const AUTOCALLBACKADDER  = AutoCallBackAdderAsset::class; // ajax powered autocomplete backed on a callback
	const AUTOCALLBACK       = AutoCallBackAsset::class;      // ajax powered autocomplete backed on a callback
	const AUTOCOMPLETE       = AutoCompleteAsset::class;
	const METATEXT			 = MetatextAsset::class;
	const FOLDER             = "amsFolderAsset";
*/

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
		'identity' => [
			'name' => 'Author',
			'type' => self::IDENTITY
		],
		'heading' => [
			'name' => 'Heading',
			'type' => self::HEADING
		],
		'subheading' => [
			'name' => 'SubHeading',
			'type' => self::SUBHEADING
		],
		'datepicker' => [
			'name' => 'Datepicker',
			'type' => self::DATEPICKER
		],
		'autocp' => [
			'name' => 'AutoComplete',
			'type' => self::AUTOCOMPLETE,
			'callback' => '/admin/ams/autocomplete/identity',
		],
		'autocp2' => [
			'name' => 'AutoComplete - Post',
			'type' => self::AUTOCOMPLETE,
			'callback' => ['url'=> '/admin/ams/autocomplete/identity', 'method' => 'post'],
		],
		'autoCallback' => [
			'name' => 'AutoCallBack',
			'type' => self::AUTOCALLBACK,
			'options' => 'checkbox, radio, textarea, textfield',
			'callback' => '/admin/ams/autocomplete/identity', // holds the callback route components
		]
	);

	public static function about() {
		return 'This asset is a collection of content items to build tabs / accordions etc.';
	}

}
