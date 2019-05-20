<?php

namespace Phpsa\Datastore\Database\Seeds;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Class PermissionRoleTableSeeder.
 */
class DatastoreRolesSeeder extends Seeder
{

    /**
     * Run the database seed.
     */
    public function run()
    {

        // Create Roles
        $cms = Role::create(['name' => 'cms']);

        // Create Permissions
        $permissions = ['manage datastore'];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
		}

		$cms->givePermissionTo('manage datastore');

    }
}
