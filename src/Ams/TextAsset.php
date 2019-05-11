<?php
namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class TextAsset extends Asset {

    public $type = 'Phpsa\Datastore\Asset\TextAsset';
    public $namespace = 'property';

    public static function html($data) {
        return '<p>' . $data['value'] . '</p>';
    }

}
?>
