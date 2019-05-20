<?php

namespace Phpsa\Datastore\Repositories;


use App\Repositories\BaseRepository;
use Phpsa\Datastore\Models\Datastore as DatastoreModel;
use Illuminate\Database\Eloquent\Collection;
use Phpsa\Datastore\Datastore;
use Phpsa\Datastore\Models\DatastoreDatastore;
use Phpsa\Datastore\Repositories\DatastoreDatastoreRepository;
/**
 * Class PermissionRepository.
 */
class DatastoreRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return DatastoreModel::class;
	}

	public function paginateAssets($assetData, $limit = 25, $pageName = 'page', $page = null){
		$this->where('type', $assetData['class']);
		return $this->paginate($limit, ['*'], $pageName, $page);

	}


	    /**
     * @param int    $limit
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
	}


	public function paginateAccepted($datastore_id, $status = NULL, $limit = 25, $pageName = 'page', $page = null){
		$ds2 = new DatastoreDatastoreRepository();
		return $ds2->paginateAccepted($datastore_id, $status, $limit, $pageName, $page);
	}

	public function paginateSearchProp($property, $property_value, $parent_type, $parent_status = NULL, $limit = 25, $pageName = 'page', $page = null){

		if($parent_status){
			$this->where('status', $published_status);
		}

		$this->newQuery()->eagerLoad()->setClauses()->setScopes();

		$this->with('properties')->whereHas('properties', function($query) use ($property, $property_value) {
			$query->where('value', $property_value);
			$query->where('key', $property);
		});

		return $this->paginate($limit, ['*'], $pageName, $page);

	}

//DatastoreDatastoreRepository

}
