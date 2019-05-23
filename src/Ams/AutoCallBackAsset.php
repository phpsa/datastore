<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class AutoCallBackAsset extends Asset {

    public $type = AutoCallBackAsset::class;
    public $namespace = 'property';

    public static function html($data) {
		$content = explode('|', $data['meta']);
        return '<span>' . $content[1] . '</span>';
	}


}
