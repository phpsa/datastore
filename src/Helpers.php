<?php

namespace Phpsa\Datastore;

use Phpsa\Datastore\Datastore;
use Phpsa\Datastore\Asset;
use Phpsa\Datastore\DatastoreException;

class Helpers {

	public static function isAssocArray(array $array){
        if (array() === $array) return false;
        return array_keys($array) !== range(0, count($array) - 1);
	}

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

	public static function getAssetList($grouped = false, $includeChildren = true)
	{
		$assets = array();
		$datastoreAssets = config("datastore.assets");
		foreach($datastoreAssets as $className){
			if (Asset::assetNamespace($className) !== 'asset') {
				throw new DatastoreException('Only assets of type assets should be used ' . $className);
			}

			$is_child = Asset::assetInfo($className, 'is_child');
			if(!$includeChildren && $is_child){
				continue;
			}

			$asset = array(
				'class'			 => $className,
				'name'			 => Asset::assetInfo($className, 'name'),
				'name_singular'	 => Asset::assetInfo($className, 'name_singular'),
				'shortname'	     => Asset::assetInfo($className, 'shortname'),
				'icon'			 => Asset::assetInfo($className, 'icon'),
				'children'		 => Asset::assetInfo($className, 'children'),
				'is_child'		 => $is_child,
				'max_instances'	 => Asset::assetInfo($className, 'max_instances'),
				'about'			 => Asset::callStatic($className, 'about'),
				'has_meta'		 => Asset::assetInfo($className, 'meta_description') !== 'off' && Asset::assetInfo($className, 'meta_keywords') !== 'off',
				'status_equals'  => Asset::assetInfo($className, 'status_equals')
			);

			if ($grouped)
			{
				$mod			 = Asset::get_module($className);
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

}