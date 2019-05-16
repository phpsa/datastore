<?php
namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;


class SubHeadingAsset extends Asset {

    public $shortname = 'SubHeading';
    public $type = SubHeadingAsset::class;
    public $namespace = 'property';

    public static function html($data) {
        return '<h4>' . $data['value'] . '</h4>';
    }
}