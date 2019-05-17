<?php

namespace Phpsa\Datastore;

use Phpsa\Datastore\Datastore;
use Phpsa\Datastore\Asset;
use Phpsa\Datastore\DatastoreException;

class Helpers {

	/**
	 * tests if teh array is an associated array or an indexed array
	 *
	 * @param array $array
	 *
	 * @return bool
	 */
	public static function isAssocArray(array $array){
        if (array() === $array) return false;
        return array_keys($array) !== range(0, count($array) - 1);
	}

	/**
	 * Splits a word by capitals and glues it together with a space or the glue
	 *
	 * @param string $string
	 * @param boolean $ucfirst
	 * @param string $glue
	 * @return string
	 */
	public static function splitByCaps($string, $ucfirst = true, $glue = false)
	{
		$pattern	 = "/(.)([A-Z])/";
		$replacement = "\\1 \\2";
		$return		 = ($ucfirst) ?
			ucfirst(preg_replace($pattern, $replacement, $string)) :
			strtolower(preg_replace($pattern, $replacement, $string));

		return ($glue) ? str_replace(' ', $glue, $return) : $return;
	}

	/**
	 * PArse out the asset type to the class namespaced version.
	 *
	 * @param [type] $queryString
	 * @param bool $key
	 *
	 * @return void
	 */
	public static function parseAssetType($queryString, $key = false){
		$assetParts = explode(".", strtolower($queryString));
		if(count($assetParts) > 2){
			throw new DatastoreException("Assets are only 2 tiers deep");
		}
		$asset = array_pop($assetParts);
		$list = self::getAssetList(true);

		$list = !empty($assetParts) && isset($list[$assetParts[0]]) ? $list[$assetParts[0]] : array_shift($list);

		if(!isset($list[$asset])){
			throw new DatastoreException("Asset not found");
		}
		return $key ? $list[$asset]['class'] : $list[$asset];
	}

	/**
	 * Gets the asset definition
	 *
	 * @param [type] $className
	 *
	 * @return void
	 */
	public static function getAssetItem($className){
		return array(
			'class'			 => $className,
			'name'			 => Self::assetInfo($className, 'name'),
			'name_singular'	 => Self::assetInfo($className, 'name_singular'),
			'shortname'	     => Self::assetInfo($className, 'shortname'),
			'icon'			 => Self::assetInfo($className, 'icon'),
			'children'		 => Self::assetInfo($className, 'children'),
			'is_child'		 => Self::assetInfo($className, 'is_child'),
			'max_instances'	 => Self::assetInfo($className, 'max_instances'),
			'about'			 => self::callStatic($className, 'about'),
			'private'		 => Self::assetInfo($className, 'private'),
			'has_meta'		 => Self::assetInfo($className, 'meta_description') !== 'off' && Self::assetInfo($className, 'meta_keywords') !== 'off',
			'status_equals'  => Self::assetInfo($className, 'status_equals')
		);
	}

	public static function getStatusEquals($className){
		$statusEquals = self::assetInfo($className, 'status_equals');
		if(!$statusEquals){
			return null;
		}
		$props = self::getAssetProps($className,$statusEquals);
		return isset($props['published'])?$props['published'] : null;
	}


	public static function getAssetProps($className, $property = null){
		$properties = self::assetInfo($className, 'properties');
		if(!$properties){
			throw new DatastoreException("No Properties found for asset");
		}
		if($property){
			return isset($properties[$property]) ? $properties[$property] : null;
		}
		return $properties;
	}

	/**
	 * List of assets
	 *
	 * @param bool $grouped groupd by type
	 * @param bool $includeChildren includ child assets
	 *
	 * @return void
	 */
	public static function getAssetList($grouped = false, $includeChildren = true)
	{
		$assets = array();
		$datastoreAssets = config("datastore.assets");
		foreach($datastoreAssets as $className){

			$asset = self::getAssetItem($className);

			if (self::assetNamespace($className) !== 'asset') {
				throw new DatastoreException('Only assets of type assets should be used ' . $className);
			}

			if(!$includeChildren && $asset['is_child']){
				continue;
			}

			if($asset['children'] && $includeChildren && !in_array($asset['children'], $datastoreAssets)){
				$child =  self::getAssetItem($asset['children']);
				if (self::assetNamespace($child['class']) !== 'asset') {
					throw new DatastoreException('Only assets of type assets should be used ' . $child['class']);
				}

				if ($grouped)
				{
					$mod			 = Helpers::getModule($child['class']);
					$assets[strtolower($mod)][strtolower($child['shortname'])]	 = $child;
				}
				else
				{
					$assets[strtolower($child['shortname'])] = $child;
				}
			}


			if ($grouped)
			{
				$mod			 = Helpers::getModule($className);
				$assets[strtolower($mod)][strtolower($asset['shortname'])]	 = $asset;
			}
			else
			{
				$assets[strtolower($asset['shortname'])] = $asset;
			}


		}

		ksort($assets);
		if($grouped){
			foreach($assets as &$group){
				uasort($group, function ($a , $b) {
					return strcmp($a['name'], $b['name']);
				});
			}
		}

		return $assets;
	}


    /**
	 * Gets the module based on the classname
	 * @param string $class
	 * @return string
	 */
	public static function getModule($class)
	{
		$parts = explode('\\', $class);
		array_pop($parts);
		$ns = '';
		if(end($parts) !== 'Ams'){
			$ns = ucfirst(array_pop($parts));
		}

		return $ns;
	}

	/**
	 * Gets the namespace of an asset!
	 * @param string $className
	 * @return string
	 */
	public static function assetNamespace($className)
	{
		return self::callStatic($className, 'getNamespace');
	}

	/**
	 * gets the url path for the asset
	 *
	 * @param [type] $className
	 *
	 * @return void
	 * @author Craig Smith <craig.smith@customd.com>
	 */
	public static function getPath($className){
		$mod = self::getModule($className);
		$sn = Self::assetInfo($className, 'shortname');
		return !empty($mod) ? strtolower($mod .'.' . $sn) : strtolower($sn);
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
	 * gets information tag from the asset
	 * @param string $classname
	 * @param mixed $lookup - what information to lookup
	 * @return mixed information
	 */
	public static function assetInfo($classname, $lookup = false)
	{
		return self::callStatic($classname, 'getinfo', array($lookup));
	}


	/**
	 * gets the classname from the path of the file
	 * @param string $file
	 * @return string
	 */
	public static function getClassnameFromPath($file)
	{
		if (!is_file(FCPATH . $file))
		{
			return false;
		}
		$fp = fopen(FCPATH . $file, 'r');

		$class	 = $buffer	 = '';
		$i		 = 0;
		while (!$class)
		{
			if (feof($fp))
				break;

			$buffer	 .= fread($fp, 512);
			$tokens	 = token_get_all($buffer);

			if (strpos($buffer, '{') === false)
				continue;

			for (; $i < count($tokens); $i++)
			{
				if ($tokens[$i][0] === T_CLASS)
				{
					for ($j = $i + 1; $j < count($tokens); $j++)
					{
						if ($tokens[$j] === '{')
						{
							$class = $tokens[$i + 2][1];
						}
					}
				}
			}
		}
		return $class;
	}
}