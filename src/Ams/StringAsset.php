<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class StringAsset extends Asset {

    public $type = StringAsset::class;
    public $namespace = 'property';

    public static function html($data) {
        return '<span>' . $data['value'] . '</span> STRING';
    }
}
