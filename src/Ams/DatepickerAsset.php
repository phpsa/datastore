<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class DatePickerAsset extends Asset {

    public $type = DatePickerAsset::class;
    public $namespace = 'property';

    public static function html($data) {
        return '<span>' . $data['value'] . '</span>';
    }
}
