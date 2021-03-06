<?php

namespace Phpsa\Datastore\Ams\Article;
use Phpsa\Datastore\Asset;
use Phpsa\Datastore\Ams\Article\ItemAsset;
class CategoryAsset extends Asset {

	public $name = 'Article Category';
	public $shortname = 'ArticleCategory';
	/* assets that can be children, a value means it is limited to that many children, -1 means unlimited  */
	public $accept = ItemAsset::class;
	public $accept_limit = "-1";

	// map our value as equal to whatever the title property is set on save.
	public $value_equals = 'category';


	public $properties = array(
		'category' => array(
			'name' => 'Category',
			'type' => self::STRING,
			'required' => true
		),
		'status' => array(
            'name' => 'Published',
            'type' => self::DROPDOWN,
			 'options' => ['published' => 'Published', 'unpublished' => 'Unpublished'],
			 'published' => ['published']
        ),

	);

	public static function about() {
		return 'This is a category to which Article Items are assigned. <br/>
			Assigning Article Items to such a category makes them easier to manage and maintain, and also
			makes it easier for your audience to find them.';
	}


	/**
	 * generate the route for this asset
	 */
	public static function route($record, $path = null){
		$page = $record->page;
		return route('frontend.ams.article.category', ['slug' => $page->slug]);
	}
}
