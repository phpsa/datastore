<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class AutoCallBackAdderAsset extends Asset {

    public $type = AutoCallBackAdderAsset::class;
    public $namespace = 'property';

    public static function html($data) {
        return '<span>' . $data['value'] . '</span>';
    }
}
