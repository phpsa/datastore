<?php
namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;


class MetatextAsset extends Asset {

    public $type = 'Phpsa\Datastore\Asset\MetatextAsset';
    public $namespace = 'property';

    public static function html($data) {
        return '<p>' . $data['value'] . '</p>';
    }

}
?>
