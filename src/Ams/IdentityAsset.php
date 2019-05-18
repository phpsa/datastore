<?php

namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;


class IdentityAsset extends Asset {

    public $type = IdentityAsset::class;
    public $namespace = 'property';

    public static function html($data) {
		$record = config('auth.providers.users.model')::find($data['value']);
		$user_label = $record ? $record->first_name . ' ' . $record->last_name : '';

        return  $user_label;
    }
}
