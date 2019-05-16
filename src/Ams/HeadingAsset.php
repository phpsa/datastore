<?php
namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;


class HeadingAsset extends Asset {

    public $shortname = 'Heading';
    public $type = HeadingAsset::class;
    public $namespace = 'property';

    public static function html($data) {
        return '<h3>' . $data['value'] . '</h3>';
    }
}