<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class DropdownAsset extends Asset {

    public $type = 'Phpsa\Datastore\Asset\DropdownAsset';
    public $namespace = 'property';

    public static function html($data) {
        return '<span>' . $data['value'] . '</span>';
    }
}
?>
