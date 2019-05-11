<?php

namespace Phpsa\Datastore\Repositories;


use App\Repositories\BaseRepository;
use Phpsa\Datastore\Models\DatastoreDatastore;
/**
 * Class PermissionRepository.
 */
class DatastoreDatastoreRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return DatastoreDatastore::class;
    }
}
