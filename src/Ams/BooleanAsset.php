<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class BooleanAsset extends Asset {


	public $shortname = 'Bool';

    public $type = BooleanAsset::class;

	public $namespace = 'property';

    public static function html($data) {
        return  !empty($data['value']) ? 'on' : 'off';
    }
}
?>
