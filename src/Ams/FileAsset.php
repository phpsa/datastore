<?php
namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

use Illuminate\Support\Facades\Storage;

class FileAsset extends Asset {

    public $type = 'Phpsa\Datastore\Ams\FileAsset';
    public $namespace = 'property';

    public static function html($data) {
        return Storage::url($data['value']);
    }


}
