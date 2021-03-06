<?php

namespace Phpsa\Datastore;

use ReflectionClass;
use Illuminate\Support\Facades\View;

use Phpsa\Datastore\DatastoreException;
use Phpsa\Datastore\Datastore;
use Phpsa\Datastore\Models\DatastorePages;
use Phpsa\Datastore\Ams\AutoCallBackAdderAsset;
use Phpsa\Datastore\Ams\AutoCallBackAsset;
use Phpsa\Datastore\Ams\AutoCompleteAsset;
use Phpsa\Datastore\Ams\BooleanAsset;
use Phpsa\Datastore\Ams\DatepickerAsset;
use Phpsa\Datastore\Ams\DropdownAsset;
use Phpsa\Datastore\Ams\FileAsset;
use Phpsa\Datastore\Ams\HeadingAsset;
use Phpsa\Datastore\Ams\HtmlAsset;
use Phpsa\Datastore\Ams\IdentityAsset;
use Phpsa\Datastore\Ams\ImageAsset;
use Phpsa\Datastore\Ams\MetatextAsset;
use Phpsa\Datastore\Ams\StringAsset;
use Phpsa\Datastore\Ams\SubHeadingAsset;
use Phpsa\Datastore\Ams\TextAsset;


use Illuminate\Support\Str;

/**
 * Asset class - this is an abstract basis for the assets that shou
 *
 * @author Craig Smith <vxdhost@gmail.com>
 */
class Asset{

	/**
	 * Constants for the different asset types!
	 */
	const ASSET              = "asset";
	const PROP               = "property";


	const AUTOCALLBACKADDER  = AutoCallBackAdderAsset::class; // ajax powered autocomplete backed on a callback
	const AUTOCALLBACK       = AutoCallBackAsset::class;      // ajax powered autocomplete backed on a callback
	const AUTOCOMPLETE       = AutoCompleteAsset::class;
	const BOOL               = BooleanAsset::class;
	const DATEPICKER         = DatepickerAsset::class;
	const DROPDOWN           = DropdownAsset::class;
	const FILE               = FileAsset::class;
	const HEADING            = HeadingAsset::class;
	const HTML               = HtmlAsset::class;
	const IDENTITY           = IdentityAsset::class;
	const IMG                = ImageAsset::class;
	const METATEXT			 = MetatextAsset::class;
	const STRING             = StringAsset::class;
	const SUBHEADING         = SubHeadingAsset::class;
	const TEXT               = TextAsset::class;


	const FOLDER             = "amsFolderAsset";

	/**
	 * help tips ypou may want to include properties
	 *
	 * @var string
	 */
	public $help             = null;

	/**
	 * Sets the required rule for a property
	 *
	 * @var bool
	 */
	public $required         = true;

	/**
	 * which property is the actual value name for your asset - target one of the properties
	 *
	 * @var string
	 */
	public $value_equals     = null;

	/**
	 * Assets namespace, most often extended as an asset, however may be a property if adding a new input type.
	 *
	 * @var string
	 */
	public $namespace        = self::ASSET;

	/**
	 * Generally left alone unless overrideing to be a property then should have the instance class
	 *
	 * @var string
	 */
	public $type             = 'asset';

	/**
	 * Properties for thie asset - used to define the property assets to use to build up the asset
	 *
	 * @var array
	 */
	public $properties       = array();

	/**
	 * Public name - This is used to identify
	 *
	 * @var [type]
	 */
	public $name             = null;

	/**
	 *  just a shortname to use instead of the full $name above
	 *
	 * @var string
	 */
	public $shortname        = null;

	/**
	 * the max number of instances, 0 means unlimited - normally used for presets like the robots.txt
	 *
	 * @var int
	 */
	public $max_instances    = 0;

	/**
	 * IE if private is true, we do not show the page link block... and idsable the meta
	 * but if private is false, we show the page link and let meta decide for itself:
	 * @var bool
	 */
	public $private          = false;

	/**
	 * @deprecated Initial -
	 * @TODO - Remove from here and from teh database object
	 *
	 * @var [type]
	 */
	public $embedded         = null;

	/**
	 * @deprecated version
	 * * @TODO - Remove from here and from teh database object
	 * @var [type]
	 */
	public $theme            = null;

	/**
	 * set to 'off' to turn it off
	 *
	 * @var [type]
	 */
	public $meta_description = null;

	 /**
	 * set to 'off' to turn it off
	 *
	 * @var [type]
	 */
	public $meta_keywords    = null;

	/**
	 * set to 'off' to turn it off
	 *
	 * @var [type]
	 */
	public $page_css         = null;

	/**
	 * set to 'off' to turn it off
	 *
	 * @var [type]
	 */
	public $page_js          = null;


	public $options          = false;						// Options for the element -- should actually be removed from the database table most likely
	public $status_equals    = null;                        // does the asset has a status linked to a prop
	public $start_date       = null;                        // does the asset has a start date linked to a prop
	public $end_date         = null;                        // does the asset has an end date linked to a prop
	public $comment_equals   = null;                        // does the asset allow comments (linked to a specific property - similar to status_equals)
	public $warning          = null;                        // field for warnings - deprected in favour of validation_messages
	//public $published		= null;							//published status
	//public $validation_messages = null					//validation messages

	public $key              = null;
	public $module           = null;
	public $value            = null;
	public $status           = null;
	public $meta             = false;                       // ancillary meta info/data - similar to options
	public $callback         = null;

	/**
	 * Link a child asset in on teh form, this will alllwo you to create a set of children on the form.
	 *
	 * @var [type]
	 */
	public $children         = null;

	/**
	 * Is this a child only asset? if marked as true does not auto display on menu options and can only be linked in as a child property to an asset.
	 *
	 * @var bool
	 */
	public $is_child         = false;

	/**
	 * Asset that can be a child, ie an article belonging to a category
	 *
	 * @var [type]
	 */
	public $accept           = null;

	/**
	 * assets that can be children, a value means it is limited to that many children, -1 means unlimited
	 * @TodO update this to better display with a selct / multiSelect null / 0 / -1 or any falsy is unlimited, else it is limited to that number...
	 * @var [type]
	 */
	public $accept_limit     = null;


	/**
	 * generate the route for this asset
	 * Overwrite this in your own assets to generate your own route
	 */
	public static function route(DatastorePages $record, $route = null){
		if(null === $route){
			$route = 'frontend.ams.page.slug';
		}
		$page = $record->page;
		return route($route, ['slug' => $page->slug]);
	}

	/**
	 * holds the about information - should be overwritten by most Assets
	 * @abstract - should be extended in children
	 */
	public static function about()
	{
		throw new DatastoreException("All Assets require an about method to describe them");
	}

	/**
	 * this is an overridable method used to create the value equals based on the field to use
	 * @param string $value
	 * @return string
	 */
	public static function valueEquals($value)
	{
		return $value;
	}

	/**
	 * Get our Default properties for this asset
	 *
	 * @return array
	 */
	public static function getDefaultProperties(){
		$obj	 = new ReflectionClass(get_called_class());
		return $obj->getDefaultProperties();
	}

	/**
	 * returns the label for the asset public identifier
	 *
	 * @todo - test if we still actually use this!!!
	 * @deprecated initially ?
	 * @return string
	 */
	public static function getValueLabel()
	{
		$props = self::getDefaultProperties();
		if(empty($props['value_equals']) || empty($props['properties'][$props['value_equals']]['name'])){
			throw new DatastoreException("value_equals is a required parameter");
		}

		$out = $props['properties'][$props['value_equals']]['name'];
		unset($obj, $props);
		return $out;
	}

	/**
	 * Gets information on the asset
	 *
	 * @param mixed $lookup - property to lookup or false for all
	 * @return mixed - array of properties, string or false.
	 */
	public static function getInfo($lookup = false)
	{
		$props = self::getDefaultProperties();

		if ($lookup)
		{
			if (isset($props[$lookup]))
			{
				$out = $props[$lookup];
				unset($obj, $props);
				return $out;
			}
			else
			{
				unset($obj, $props);
				return false;
			}
		}
		unset($obj);
		return $props;
	}

	/**
	 * Returns the Assets view path syntax
	 *
	 * @param string $type
	 *
	 * @return string
	 */
	public static function getAssetView(string $type = 'render') : string
	{
		$className = class_basename(get_called_class());
		$ns = str_replace('\\', '', __NAMESPACE__);
		return Str::kebab($ns) . "::{$type}." . Str::kebab($className);
	}

	/**
	 * Creates the asset form
	 *
	 * @param array $args - form data
	 * @param boolean $injectedform - if is a sub-form of another asset
	 * @return string - the form html
	 * @TODO :: todo not workin gyet
	 * Should most likely parse throug to blade or laravel somewhere
	 */
	public static function form($args, $injectedform = false)
	{
		$template = self::getAssetView('form');
		if (empty($args['unique_id']))
		{
			$args['unique_id'] = uniqid();
		}
		$data['data']			 = $args;
		$data['asset_classname'] = $injectedform;
		return View::make($template, $data)->render();
	}

	/**
	 * Renders the asset's markup
	 *
	 * @param array $args
	 * @param string $params
	 * @return string
	 * * Should most likely parse throug to blade or laravel somewhere
	 */
	public static function render($args = [], $params = false)
	{

		if(is_string($params)){
			$template = $params;
		} else {
			$template = is_array($params) && !empty($params['view']) ? $params['view'] : self::getAssetView();
		}

		$params['assetClass'] = get_called_class();
		$args['params'] = $params;

		return View::exists($template) ? View::make($template, $args)->render() : static::html($args);
	}

	/**
	 * Retrieve the current assets namespace
	 * @return string - current namespace
	 */
	public static function getNamespace()
	{
		return self::getInfo('namespace');
	}

	/**
	 * Creates a new Asset Instance
	 * @param string $className
	 * @param array $args
	 * @return Asset
	 * @TODO Use Namespacing to autoload!!!
	 */
	public static function factory($className, $args = array())
	{
		$ref = new ReflectionClass($className);
		return $ref->newInstanceArgs($args);
	}

	/**
	 * gets the url path for the asset
	 *
	 * @param [type] $className
	 *
	 * @return void
	 * @author Craig Smith <craig.smith@customd.com>
	 */
	public static function getPath($className = null){
		if(null === $className) {
			$className = get_called_class();
		}
		return Helpers::getPath($className);
	}

}