<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class AutoCallBackAdderAsset extends Asset {

    public $type = AutoCallBackAdderAsset::class;
    public $namespace = 'property';

    public static function html($data) {
		$strings = [];

		foreach($data['meta'] as $k){
			$parts = explode("|", $k);
			$strings[] = $parts[1];
		}
        return '<span>' . implode(", ", $strings) . '</span>';
    }
}
