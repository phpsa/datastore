<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class DropdownAsset extends Asset {

    public $type = DropdownAsset::class;
    public $namespace = 'property';

    public static function html($data) {
        return '<span>' . $data['value'] . '</span>';
    }
}