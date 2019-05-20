<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class AutoCompleteAsset extends Asset {

    public $type = AutoCompleteAsset::class;
    public $namespace = 'property';

    public static function html($data) {
        return '<span>' . $data['value'] . '</span>';
    }
}