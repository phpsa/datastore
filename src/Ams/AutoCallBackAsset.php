<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class AutoCallBackAsset extends Asset {

    public $type = AutoCallBackAsset::class;
    public $namespace = 'property';

    public static function html($data) {
        return '<span>' . $data['value'] . '</span>';
    }
}
