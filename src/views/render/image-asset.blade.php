<?php
	$src = $params['assetClass']::getImageUrl($value, !empty($params['width']) ? $params['width']: null,!empty($params['height']) ? $params['height']: null);
	$image = html()->img()->src($src);
	foreach(['id','class','onError','alt','title'] as $prop){
		if(!empty($params[$prop])){
			$image = $image->attribute($prop, $params[$prop]);
		}
	}
?>

{{ $image }}
