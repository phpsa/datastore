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

	public function paginateAccepted($datastore_id, $status = NULL, $limit = 25, $pageName = 'page', $page = null){
		$this->where('datastore2_id', $datastore_id)->with('datastore');

			$this->whereHas('datastore', function($query) use ($status) {
				if($status){
					if(!is_array($status)){
						$status = [$status];
					}
					$query->whereIn('status', $status);
				}
			});
		return $this->paginate($limit, ['*'], $pageName, $page);
	}

	/*
	$ids = DatastoreDatastore::whereHas('child', function($query){
			$query->where('status', '=', 'published');
		})->where('datastore2_id', $page->asset)->get(['datastore_id'])->pluck('datastore_id')->toArray();
		*/
}
