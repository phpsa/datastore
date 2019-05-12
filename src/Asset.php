<?php

namespace Phpsa\Datastore;

use Phpsa\Datastore\DatastoreException;
use ReflectionClass;

use Phpsa\Datastore\Ams\StringAsset;
use Phpsa\Datastore\Ams\HtmlAsset;
use Phpsa\Datastore\Ams\TextAsset;
use Phpsa\Datastore\Ams\DropdownAsset;
use Phpsa\Datastore\Ams\MetatextAsset;
use Phpsa\Datastore\Ams\BooleanAsset;
use Phpsa\Datastore\Ams\FileAsset;
use Phpsa\Datastore\Ams\ImageAsset;

use Illuminate\Support\Facades\View;

class Asset{

	/**
	 * Constants for the different asset types!
	 */
	const ASSET              = "asset";
	const PROP               = "property";

	const STRING             = StringAsset::class;
	const TEXT               = TextAsset::class;
	const HTML               = HtmlAsset::class;
	const IMG                = ImageAsset::class;
	const FILE               = FileAsset::class;
	const BOOL               = BooleanAsset::class;
	const DROPDOWN           = DropdownAsset::class;
	const METATEXT			 = MetatextAsset::class;
	const FOLDER             = "amsFolderAsset";
	const HEADING            = "amsHeadingAsset";
	const DATEPICKER         = "amsDatepickerAsset";
	const IDENTITY           = "amsIdentityAsset";
	const AUTOCALLBACK       = "amsAutocallbackAsset";      // ajax powered autocomplete backed on a callback
	const AUTOCALLBACKADDER  = "amsAutocallbackadderAsset"; // ajax powered autocomplete backed on a callback
	const AUTOCOMPLETE       = "amsAutocompleteAsset";

	public $help             = null;                        // help tips ypou may want to include
	public $value_equals     = null;


	public $name             = null;
	public $shortname        = null;                        // just a shortname to use instead of the full $name above
	public $embedded         = null;
	public $theme            = null;
	public $key              = null;
	public $namespace        = self::ASSET;
	public $type             = 'asset';
	public $module           = null;
	public $private          = false;
	public $value            = null;
	public $status           = null;
	public $options          = false;
	public $meta             = false;                       // ancillary meta info/data - similar to options
	public $callback         = null;
	public $meta_description = null;                        // set to 'off' to turn it off
	public $meta_keywords    = null;                        // set to 'off' to turn it off
	public $page_css         = null;                        // set to 'off' to turn it off
	public $page_js          = null;                        // set to 'off' to turn it off
	public $children         = null;                        // what assets can be immediate children [not shared, similar to properties]
	public $is_child         = false;                       // what assets can be immediate children [not shared, similar to properties]
	public $accept           = null;
	public $accept_limit     = null;
	public $status_equals    = null;                        // does the asset has a status linked to a prop
	public $start_date       = null;                        // does the asset has a start date linked to a prop
	public $end_date         = null;                        // does the asset has an end date linked to a prop
	public $comment_equals   = null;                        // does the asset allow comments (linked to a specific property - similar to status_equals)
	public $warning          = null;                        // field for warnings
	public $required         = true;                       // property required - defaults to true btw?
	public $max_instances     = 0;                           // the max number of instances, 0 means unlimited - normally used for presets like the robots.txt
	public $properties       = array();


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
	 * @return string
	 */
	public static function getValueLabel()
	{
		$props = self::getDefaultProperties();

		if ($props['value_equals'])
		{
			$out = $props['properties'][$props['value_equals']]['name'];
			unset($obj, $props);
			return $out;
		}
		else
		{
			unset($obj, $props);
			return 'Name';
		}
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
	 * holds the about information - should be overwritten by most Assets
	 * @abstract - should be extended in children
	 */
	public static function about()
	{
		throw new DatastoreException("All Assets require an about method to describe them");
	}



	/**
	 * Returns the Class's filename
	 * @return string
	 * @todo test - split by caps / namespace
	 */
	public static function getFilename($type = 'render')
	{
		//break the class to class and namespace
		$parts = explode('\\', get_called_class());
		$className = array_pop($parts);
		$last = array_pop($parts);
		//remove ams as we do not want to force it as part of our path.
		if(trim(strtolower($last)) !== 'ams'){
			$parts.push($last);
		}
		$ns = strtolower(implode("-", $parts));

		$name = self::_splitByCaps($className, false);
		return $ns . '::' . $type . '.' . self::_splitByCaps($className, false, "-");
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
		$template = self::getFilename('form');
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
	 * @param string $template
	 * @return string
	 * * Should most likely parse throug to blade or laravel somewhere
	 */
	public static function render($args = [], $template = false)
	{
		if (!$template)
		{
			$template = self::getFilename();
		}
		$data['data'] = $args;
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
	 * Splits a word by capitals and glues it together with a space or the glue
	 *
	 * @param string $string
	 * @param boolean $ucfirst
	 * @param string $glue
	 * @return string
	 */
	public static function _splitByCaps($string, $ucfirst = true, $glue = false)
	{
		$pattern	 = "/(.)([A-Z])/";
		$replacement = "\\1 \\2";
		$return		 = ($ucfirst) ?
			ucfirst(preg_replace($pattern, $replacement, $string)) :
			strtolower(preg_replace($pattern, $replacement, $string));

		return ($glue) ? str_replace(' ', $glue, $return) : $return;
	}

	/**
	 * Creates a new Asset Instance
	 * @param string $classname
	 * @param array $args
	 * @return DF_Asset
	 * @TODO Use Namespacing to autoload!!!
	 */
	public static function factory($classname, $args = array())
	{

		$ref = new ReflectionClass($classname);
		return $ref->newInstanceArgs($args);
	}

		/**
	 * Gets the module based on the classname
	 * @param string $class
	 * @return string
	 */
	public static function get_module($class)
	{

		$parts = explode('\\', $class);
		array_pop($parts);
		$ns = '';
		if(end($parts) !== 'Ams'){
			//array_pop($parts);
			$ns = ucfirst(array_pop($parts));
		}

		return $ns;
	}


	/**
	 * gets information tag from the asset
	 * @param string $assetclassname
	 * @param mixed $lookup - what information to lookup
	 * @return mixed information
	 */
	public static function assetInfo($assetclassname, $lookup = false)
	{
		return self::callStatic($assetclassname, 'getinfo', array($lookup));
	}

	/**
	 * Calls a static method
	 * @param string $classname class to call
	 * @param string $method method to call
	 * @param array $params_array method parameters
	 * @return mixed
	 */
	public static function callStatic($classname, $method, $params_array = array())
	{
		return call_user_func_array(array($classname, $method), $params_array);
	}

	/**
	 * Legacy - to be worked out
	 * @param string $value
	 * @return string
	 */
	public static function valueEquals($value)
	{
		return $value;
	}

	/**
	 * Gets the namespace of an asset!
	 * @param string $assetclassname
	 * @return string
	 */
	public static function assetNamespace($assetclassname)
	{
		return self::callStatic($assetclassname, 'getNamespace');
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
		$mod = self::get_module($className);
		$sn = self::assetInfo($className, 'shortname');
		return !empty($mod) ? strtolower($mod .'.' . $sn) : strtolower($sn);
	}

}