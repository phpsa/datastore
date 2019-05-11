<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

class HtmlAsset extends Asset {

    public $type = 'Phpsa\Datastore\Asset\HtmlAsset';
    public $namespace = 'property';
	public $options = ["formatting", "bold", "italic", "deleted", "unorderedlist","orderedlist", "image", "file", "link"];

    // return it raw
    public static function html($data) {
        return $data['value'];
    }
}
