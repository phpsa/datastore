<?php
namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;


class MetatextAsset extends Asset {

    public $type = MetatextAsset::class;
    public $namespace = 'property';

    public static function html($data) {
        return '<p>' . $data['value'] . '</p>';
    }

}
?>
