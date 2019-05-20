<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;
use Phpsa\Datastore\Ams\SubHeadingAsset;

class BlockAsset extends Asset {

	public $name = 'Block Content';
	public $shortname = 'Block';
	public $is_child = false;

	public $page_js = 'off';
	public $page_css = 'on';

	public $private = true;

	public $value_equals = 'title';

	public $properties = array(
		'title' => array(
			'name' => 'Title',
			'type' => self::STRING,
			'help' => 'Title of your block',
			'required' => true,
			'validation_messages' => 'A Title Is required'
		),
		'heading' => [
			'name' => 'Heading',
			'type' => self::HEADING
		],
		'subheader' => [
			'name' => 'SubHeading',
			'type' => SubHeadingAsset::class
		],
		'content' => array(
			'name' => 'Content',
			'type' => self::HTML
		)
	);

	public static function about() {
		return 'A basic Block content asset, allows for injecting editable zones';
	}

			/**
	 * generate the route for this asset
	 */
	public static function route($record, $path = null){

		$path = 'frontend.ams.page.slug';
		$page = $record->page;
		return route($path, ['slug' => $page->slug]);
	}


}
