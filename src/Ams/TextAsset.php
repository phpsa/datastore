<?php
namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class TextAsset extends Asset {

    public $type = TextAsset::class;
    public $namespace = 'property';

    public static function html($data) {
        return '<p>' . $data['value'] . '</p>';
    }

}