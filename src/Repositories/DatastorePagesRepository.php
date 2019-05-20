<?php

namespace Phpsa\Datastore\Repositories;


use App\Repositories\BaseRepository;
use Phpsa\Datastore\Models\DatastorePages;
/**
 * Class PermissionRepository.
 */
class DatastorePagesRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return DatastorePages::class;
    }
}
